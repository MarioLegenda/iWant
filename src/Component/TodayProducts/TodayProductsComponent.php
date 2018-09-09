<?php

namespace App\Component\TodayProducts;

use App\Component\Request\Model\TodayProduct;
use App\Component\TodayProducts\Model\TodayProduct as TodayProductModel;
use App\Component\Selector\Ebay\ProductFetcher as EbayProductFetcher;
use App\Component\Selector\Etsy\ProductFetcher as EtsyProductFetcher;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class TodayProductsComponent
{
    /**
     * @var EbayProductFetcher $ebayProductFetcher
     */
    private $ebayProductFetcher;
    /**
     * @var EtsyProductFetcher $etsyProductFetcher
     */
    private $etsyProductFetcher;
    /**
     * TodayProductsComponent constructor.
     * @param EbayProductFetcher $ebayProductFetcher
     * @param EtsyProductFetcher $etsyProductFetcher
     */
    public function __construct(
        EbayProductFetcher $ebayProductFetcher,
        EtsyProductFetcher $etsyProductFetcher
    ) {
        $this->ebayProductFetcher = $ebayProductFetcher;
        $this->etsyProductFetcher = $etsyProductFetcher;
    }

    /**
     * @param TodayProduct $model
     * @return array
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTodaysProducts(TodayProduct $model): array
    {
        return array_merge(
            $this->createEbayProducts()->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION),
            $this->createEtsyProducts()->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
        );
    }
    /**
     * @return iterable|TypedArray
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function createEbayProducts(): iterable
    {
        return $this->ebayProductFetcher->getProducts();
    }
    /**
     * @return iterable|TypedArray
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     */
    private function createEtsyProducts(): iterable
    {
        return $this->etsyProductFetcher->getProducts();
    }
}