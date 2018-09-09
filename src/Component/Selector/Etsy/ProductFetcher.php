<?php

namespace App\Component\Selector\Etsy;

use App\Component\Selector\Etsy\Selector\FindAllFeaturedListings;
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

        $randomShopIds = array_rand($applicationShopIds, 4);

        foreach ($randomShopIds as $index) {
            /** @var ApplicationShop $applicationShop */

            $applicationShop = $this->applicationShopRepository->find(
                $applicationShopIds[$index]
            );

            $this->productSelector
                ->attach(new FindAllFeaturedListings($applicationShop));

            $this->productSelector->notify();
        }

        return $this->productSelector->getProductResponseModels();
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

            $title = $singleModel->getTitle();
            $imageUrl = 'NaN';
            $shopName = 'Shop name';
            $price = $singleModel->getPrice();
            $viewItemUrl = $singleModel->getUrl();
            $shopType = MarketplaceType::fromValue('Etsy');

            $products[] = new TodayProduct(
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
}