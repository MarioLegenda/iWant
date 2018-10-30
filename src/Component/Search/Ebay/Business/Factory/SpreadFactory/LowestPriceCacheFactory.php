<?php

namespace App\Component\Search\Ebay\Business\Factory\SpreadFactory;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;

class LowestPriceCacheFactory
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
}