<?php

namespace App\Component\TodayProducts;

use App\Component\Request\Model\TodayProduct;
use App\Component\TodayProducts\Model\TodayProduct as TodayProductModel;
use App\Component\Selector\Ebay\ProductFetcher as EbayProductFetcher;

class TodayProductsComponent
{
    /**
     * @var EbayProductFetcher $ebayProductFetcher
     */
    private $ebayProductFetcher;
    /**
     * TodayProductsComponent constructor.
     * @param EbayProductFetcher $ebayProductFetcher
     */
    public function __construct(
        EbayProductFetcher $ebayProductFetcher
    ) {
        $this->ebayProductFetcher = $ebayProductFetcher;
    }
    /**
     * @param TodayProduct $model
     */
    public function getTodaysProducts(TodayProduct $model)
    {
        $ebayProducts = $this->createEbayProducts();
    }
    /**
     * @return iterable|TodayProductModel
     */
    private function createEbayProducts()
    {
        return $this->ebayProductFetcher->getProducts();
    }
}