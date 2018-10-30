<?php

namespace App\Component\Search\Ebay\Business\Factory\SpreadFactory;

use App\Component\Search\Ebay\Business\Factory\RequestModelFactory;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class SpreadFactory
{
    /**
     * @var LowestPriceCacheFactory $lowestPriceCacheFactory
     */
    private $lowestPriceCacheFactory;
    /**
     * @var BestMatchCacheFactory $bestMatchCacheFactory
     */
    private $bestMatchCacheFactory;
    /**
     * SpreadFactory constructor.
     * @param LowestPriceCacheFactory $lowestPriceCacheFactory
     * @param BestMatchCacheFactory $bestMatchCacheFactory
     */
    public function __construct(
        LowestPriceCacheFactory $lowestPriceCacheFactory,
        BestMatchCacheFactory $bestMatchCacheFactory
    ) {
        $this->lowestPriceCacheFactory = $lowestPriceCacheFactory;
        $this->bestMatchCacheFactory = $bestMatchCacheFactory;
    }
    /**
     * @param SearchModel $model
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Throwable
     */
    public function spreadSearch(SearchModel $model)
    {
        $bestMatchModel = RequestModelFactory::createFromArray([
            'keyword' => $model->getKeyword(),
            'lowestPrice' => false,
            'highestPrice' => false,
            'highQuality' => false,
            'bestMatch' => true,
            'globalId' => $model->getGlobalId(),
            'pagination' => [
                'limit' => $model->getPagination()->getLimit(),
                'page' => $model->getPagination()->getPage(),
            ],
            'viewType' => $model->getViewType(),
        ]);

        $lowestPriceModel = RequestModelFactory::createFromArray([
            'keyword' => $model->getKeyword(),
            'lowestPrice' => true,
            'highestPrice' => false,
            'highQuality' => false,
            'bestMatch' => false,
            'globalId' => $model->getGlobalId(),
            'pagination' => [
                'limit' => $model->getPagination()->getLimit(),
                'page' => $model->getPagination()->getPage(),
            ],
            'viewType' => $model->getViewType(),
        ]);

        $this->bestMatchCacheFactory->storeInCache($bestMatchModel);

        $this->lowestPriceCacheFactory->storeInCache(
            $bestMatchModel,
            $lowestPriceModel
        );
    }
}