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

    public function spreadSearch(SearchModel $model)
    {
        $bestMatchModel = RequestModelFactory::createFromArray([
        ]);
    }
}