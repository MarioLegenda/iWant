<?php

namespace App\Component\Search\Ebay\Business\Factory\SpreadFactory;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class BestMatchCacheFactory
{
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * @var PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     */
    private $preparedResponseCacheImplementation;
    /**
     * LowestPriceCacheFactory constructor.
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     */
    public function __construct(
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        PreparedResponseCacheImplementation $preparedResponseCacheImplementation
    ) {
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->preparedResponseCacheImplementation = $preparedResponseCacheImplementation;
    }

    public function storeInCache(SearchModel $model)
    {

    }
}