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

    public function getProducts(): iterable
    {
        $products = [];

        $this->blueDot->useRepository('util');

        $promise = $this->blueDot->execute('simple.select.get_application_shop_ids_by_marketplace', [
            'marketplace' => (string) MarketplaceType::fromValue('Ebay'),
        ]);

        $applicationShopIds = $promise->getResult()['data']['id'];

        $randomShopIds = array_rand($applicationShopIds, 4);

        foreach ($randomShopIds as $index) {
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

            $this->productSelector->notify();
        }
    }
}