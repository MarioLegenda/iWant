<?php

namespace App\Component\TodayProducts\Selector\Etsy;

use App\Component\TodayProducts\Selector\Etsy\Factory\ProductModelFactory;
use App\Component\TodayProducts\Selector\Etsy\Selector\FindAllShopListingsActive;
use App\Component\TodayProducts\Selector\Etsy\Selector\FindAllShopListingsFeatured;
use App\Component\TodayProducts\Selector\Etsy\Selector\SearchProduct;
use App\Component\TodayProducts\Model\SearchResult;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
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
     * @var ProductModelFactory $productModelFactory
     */
    private $productModelFactory;
    /**
     * ProductFetcher constructor.
     * @param BlueDot $blueDot
     * @param ApplicationShopRepository $applicationShopRepository
     * @param ProductSelector $productSelector
     * @param ProductModelFactory $productModelFactory
     */
    public function __construct(
        BlueDot $blueDot,
        ApplicationShopRepository $applicationShopRepository,
        ProductSelector $productSelector,
        ProductModelFactory $productModelFactory
    ) {
        $this->blueDot = $blueDot;
        $this->applicationShopRepository = $applicationShopRepository;
        $this->productSelector = $productSelector;
        $this->productModelFactory = $productModelFactory;
    }
    /**
     * @return iterable
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
     * @param TypedArray $searchProducts
     * @return TypedArray
     */
    public function createTodaysProductModels(TypedArray $searchProducts): TypedArray
    {
        $products = TypedArray::create('integer', SearchResult::class);

        /** @var SearchProduct $searchProduct */
        foreach ($searchProducts as $searchProduct) {
            /** @var Result $singleModel */
            $singleModel = $searchProduct->getResponseModels()->getResults()[0];

            $products[] = $this->productModelFactory->createModel(
                $singleModel,
                $searchProduct->getApplicationShop(),
                $searchProduct->getShippingInformation(),
                $searchProduct->getImage()
            );
        }

        return $products;
    }
    /**
     * @return TypedArray
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
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
                $this->productSelector->notify($applicationShop);

                $shopsSuccessCount++;
            } catch (\Exception $e) {}

            if ($shopsSuccessCount === 4) {
                break;
            }
        }

        return $this->productSelector->getProductResponseModels();
    }
}