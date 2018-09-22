<?php

namespace App\Component\TodayProducts;

use App\Cache\Implementation\TodayProductCacheImplementation;
use App\Web\Model\Request\TodayProductRequestModel;
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
     * @var TodayProductCacheImplementation $todayProductCacheImplementation
     */
    private $todayProductCacheImplementation;
    /**
     * TodayProductsComponent constructor.
     * @param EbayProductFetcher $ebayProductFetcher
     * @param EtsyProductFetcher $etsyProductFetcher
     * @param TodayProductCacheImplementation $todayProductCacheImplementation
     */
    public function __construct(
        EbayProductFetcher $ebayProductFetcher,
        EtsyProductFetcher $etsyProductFetcher,
        TodayProductCacheImplementation $todayProductCacheImplementation
    ) {
        $this->ebayProductFetcher = $ebayProductFetcher;
        $this->etsyProductFetcher = $etsyProductFetcher;
        $this->todayProductCacheImplementation = $todayProductCacheImplementation;
    }
    /**
     * @param TodayProductRequestModel $model
     * @return array
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTodaysProducts(TodayProductRequestModel $model): array
    {
        if ($this->todayProductCacheImplementation->isStored($model->getStoredAt())) {
            $cacheResponse = $this->todayProductCacheImplementation->getStored($model->getStoredAt());

            return json_decode($cacheResponse, true);
        }

        $responseData = [
            'ebay' => $this->createEbayProducts()->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION),
            'etsy' => $this->createEtsyProducts()->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION),
        ];

        $this->todayProductCacheImplementation->store(
            $model->getStoredAt(),
            json_encode($responseData)
        );

        return $responseData;
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
     * @return iterable
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function createEtsyProducts(): iterable
    {
        return $this->etsyProductFetcher->getProducts();
    }
}