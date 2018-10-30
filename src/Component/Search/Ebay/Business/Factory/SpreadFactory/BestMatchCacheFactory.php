<?php

namespace App\Component\Search\Ebay\Business\Factory\SpreadFactory;

use App\Component\Search\Ebay\Business\PreparedEbayResponseAbstraction;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class BestMatchCacheFactory
{
    /**
     * @var PreparedEbayResponseAbstraction $preparedEbayResponseAbstraction
     */
    private $preparedEbayResponseAbstraction;
    /**
     * LowestPriceCacheFactory constructor.
     * @param PreparedEbayResponseAbstraction $preparedEbayResponseAbstraction
     */
    public function __construct(
        PreparedEbayResponseAbstraction $preparedEbayResponseAbstraction
    ) {
        $this->preparedEbayResponseAbstraction = $preparedEbayResponseAbstraction;
    }
    /**
     * @param SearchModel $model
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Throwable
     */
    public function storeInCache(SearchModel $model): void
    {
        $this->preparedEbayResponseAbstraction->getPreparedResponse($model);
    }
}