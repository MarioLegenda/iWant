<?php

namespace App\Component\Selector\Ebay;

use App\Component\Selector\Ebay\Selector\SelectorFive;
use App\Component\Selector\Ebay\Selector\SelectorFour;
use App\Component\Selector\Ebay\Selector\SelectorOne;
use App\Component\Selector\Ebay\Selector\SelectorSix;
use App\Component\Selector\Ebay\Selector\SelectorThree;
use App\Component\Selector\Ebay\Selector\SelectorTwo;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use BlueDot\BlueDot;

class ProductFetcher
{
    /**
     * @var BlueDot $blueDot
     */
    private $blueDot;
    /**
     * @var ApplicationShopRepository $applicationShopRepository
     */
    private $applicationShopRepository;
    /**
     * @var ProductSelector $productSelector
     */
    private $productSelector;
    /**
     * ProductFetcher constructor.
     * @param BlueDot $blueDot
     * @param ApplicationShopRepository $applicationShopRepository
     * @param ProductSelector $productSelector
     */
    public function __construct(
        BlueDot $blueDot,
        ApplicationShopRepository $applicationShopRepository,
        ProductSelector $productSelector
    ) {
        $this->blueDot = $blueDot;
        $this->applicationShopRepository = $applicationShopRepository;
        $this->productSelector = $productSelector;
    }
    /**
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getProducts(): iterable
    {
        $responseModels = $this->getResponseModels();

        return $this->createTodaysProductModels($responseModels);
    }
    /**
     * @param TypedArray $responseModels
     * @return TypedArray
     */
    private function createTodaysProductModels(TypedArray $responseModels): TypedArray
    {
        $todayProductModels = TypedArray::create('integer', TodayProduct::class);

        /** @var XmlFindingApiResponseModel $responseModel */
        foreach ($responseModels as $responseModel) {
            /** @var Item $singleItem */
            $singleItem = $responseModel->getSearchResults()[0];

            $itemId = (string) $singleItem->getItemId();
            $title = $singleItem->getTitle();
            $imageUrl = $singleItem->getGalleryUrl();
            $shopName = $singleItem->getSellerInfo()->getSellerUsername();
            $price = $singleItem->getSellingStatus()->getCurrentPrice();
            $viewItemUrl = $singleItem->getViewItemUrl();

            $todayProductModels[] = new TodayProduct(
                $itemId,
                $title,
                $imageUrl,
                $shopName,
                $price['currentPrice'],
                $viewItemUrl,
                MarketplaceType::fromValue('Ebay')
            );
        }

        return $todayProductModels;
    }
    /**
     * @return TypedArray
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getResponseModels(): TypedArray
    {
        $this->blueDot->useRepository('util');

        $promise = $this->blueDot->execute('simple.select.get_application_shop_ids_by_marketplace', [
            'marketplace' => (string) MarketplaceType::fromValue('Ebay'),
        ]);

        $applicationShopIds = $promise->getResult()['data']['id'];

        $shopSuccessCount = 0;
        $visitedShops = [];
        for (;;) {
            $index = array_rand($applicationShopIds, 1);

            if (in_array($index, $visitedShops) === true) {
                continue;
            }

            $visitedShops[] = $index;

            /** @var ApplicationShop $applicationShop */
            $applicationShop = $this->applicationShopRepository->find(
                $applicationShopIds[$index]
            );

            $this->productSelector
                ->attach(new SelectorOne($applicationShop))
                ->attach(new SelectorTwo($applicationShop))
                ->attach(new SelectorThree($applicationShop))
                ->attach(new SelectorFour($applicationShop))
                ->attach(new SelectorFive($applicationShop))
                ->attach(new SelectorSix($applicationShop));

            try {
                $this->productSelector->notify();

                $shopSuccessCount++;
            } catch (\Exception $e) {}

            if ($shopSuccessCount === 4) {
                break;
            }
        }

        return $this->productSelector->getProductResponseModels();
    }
}