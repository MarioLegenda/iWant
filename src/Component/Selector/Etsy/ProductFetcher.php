<?php

namespace App\Component\Selector\Etsy;

use App\Component\Selector\Etsy\Selector\FindAllShopListingsActive;
use App\Component\Selector\Etsy\Selector\FindAllShopListingsFeatured;
use App\Component\Selector\Type\Nan;
use App\Component\TodayProducts\Model\Image;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ResponseItem\Result;
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
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     */
    public function getProducts(): iterable
    {
        $responseModels = $this->getResponseModels();

        return $this->createTodaysProductModels($responseModels);
    }
    /**
     * @param TypedArray $models
     * @return TypedArray
     */
    public function createTodaysProductModels(TypedArray $models): TypedArray
    {
        $products = TypedArray::create('integer', TodayProduct::class);

        /** @var EtsyApiResponseModelInterface $model */
        foreach ($models as $model) {
            /** @var Result $singleModel */
            $singleModel = $model->getResults()[0];

            $itemId = (string) $singleModel->getListingId();
            $title = $singleModel->getTitle();
            $imageUrl = new Image(Nan::fromValue());
            $shopName = 'Shop name';
            $price = $singleModel->getPrice();
            $viewItemUrl = $singleModel->getUrl();
            $shopType = MarketplaceType::fromValue('Etsy');

            $products[] = new TodayProduct(
                $itemId,
                $title,
                $imageUrl,
                $shopName,
                $price,
                $viewItemUrl,
                $shopType
            );
        }

        return $products;
    }
    /**
     * @return TypedArray
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     */
    private function getResponseModels(): TypedArray
    {
        $this->blueDot->useRepository('util');

        $promise = $this->blueDot->execute('simple.select.get_application_shop_ids_by_marketplace', [
            'marketplace' => (string) MarketplaceType::fromValue('Etsy'),
        ]);

        $applicationShopIds = $promise->getResult()['data']['id'];

        $shopsSuccessCount = 0;
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
                ->attach(new FindAllShopListingsFeatured($applicationShop))
                ->attach(new FindAllShopListingsActive($applicationShop));

            try {
                $this->productSelector->notify();

                $shopsSuccessCount++;
            } catch (\Exception $e) {}

            if ($shopsSuccessCount === 4) {
                break;
            }
        }

        return $this->productSelector->getProductResponseModels();
    }
}