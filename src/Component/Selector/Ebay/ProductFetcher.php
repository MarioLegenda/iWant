<?php

namespace App\Component\Selector\Ebay;

use App\Component\Selector\Ebay\Selector\SelectorOne;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
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
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * ProductFetcher constructor.
     * @param BlueDot $blueDot
     * @param ApplicationShopRepository $applicationShopRepository
     * @param ProductSelector $productSelector
     * @param FindingApiEntryPoint $findingApiEntryPoint
     */
    public function __construct(
        BlueDot $blueDot,
        ApplicationShopRepository $applicationShopRepository,
        ProductSelector $productSelector,
        FindingApiEntryPoint $findingApiEntryPoint
    ) {
        $this->blueDot = $blueDot;
        $this->applicationShopRepository = $applicationShopRepository;
        $this->productSelector = $productSelector;
        $this->findingApiEntryPoint = $findingApiEntryPoint;
    }

    public function getProducts(): iterable
    {
        $products = TypedArray::create('integer', TodayProduct::class);

        $this->blueDot->useRepository('util');

        $promise = $this->blueDot->execute('simple.select.get_application_shop_ids_by_marketplace', [
            'marketplace' => (string) MarketplaceType::fromValue('Amazon'),
        ]);

        $applicationShopIds = $promise->getResult()['data']['id'];

        for (;;) {
            if (count($products) === 4) {
                break;
            }

            $applicationShopId = $applicationShopIds[array_rand($applicationShopIds)];
            /** @var ApplicationShop $applicationShop */
            $applicationShop = $this->applicationShopRepository->find($applicationShopId);

            $this->productSelector
                ->attach(new SelectorOne($applicationShop));

            $products[] = $this->productSelector->notify();
        }

        dump($products);
        die();
    }
}