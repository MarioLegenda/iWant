<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\Filter\FixedPriceFilter;
use App\Component\Search\Ebay\Business\Filter\HighestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\SearchQueryRegexFilter;
use App\Component\Search\Ebay\Business\Filter\WatchCountDecreaseFilter;
use App\Component\Search\Ebay\Business\Filter\WatchCountFilter;
use App\Component\Search\Ebay\Business\Filter\WatchCountIncreaseFilter;

class FilterResolver
{
    /**
     * @var FixedPriceFilter $fixedPriceFilter
     */
    private $fixedPriceFilter;
    /**
     * @var HighestPriceFilter $highestPriceFilter
     */
    private $highestPriceFilter;
    /**
     * @var LowestPriceFilter $lowestPriceFilter
     */
    private $lowestPriceFilter;
    /**
     * @var SearchQueryRegexFilter $searchQueryRegexFilter
     */
    private $searchQueryRegexFilter;
    /**
     * @var WatchCountIncreaseFilter $watchCountIncreaseFilter
     */
    private $watchCountIncreaseFilter;
    /**
     * @var WatchCountDecreaseFilter $watchCountDecreaseFilter
     */
    private $watchCountDecreaseFilter;
    /**
     * FilterResolver constructor.
     * @param HighestPriceFilter $highestPriceFilter
     * @param LowestPriceFilter $lowestPriceFilter
     * @param SearchQueryRegexFilter $searchQueryRegexFilter
     * @param FixedPriceFilter $fixedPriceFilter
     * @param WatchCountIncreaseFilter $watchCountIncreaseFilter
     * @param WatchCountDecreaseFilter $watchCountDecreaseFilter
     */
    public function __construct(
        HighestPriceFilter $highestPriceFilter,
        LowestPriceFilter $lowestPriceFilter,
        SearchQueryRegexFilter $searchQueryRegexFilter,
        FixedPriceFilter $fixedPriceFilter,
        WatchCountIncreaseFilter $watchCountIncreaseFilter,
        WatchCountDecreaseFilter $watchCountDecreaseFilter
    ) {
        $this->highestPriceFilter = $highestPriceFilter;
        $this->lowestPriceFilter = $lowestPriceFilter;
        $this->searchQueryRegexFilter = $searchQueryRegexFilter;
        $this->fixedPriceFilter = $fixedPriceFilter;
        $this->watchCountIncreaseFilter = $watchCountIncreaseFilter;
        $this->watchCountDecreaseFilter = $watchCountDecreaseFilter;
    }
    /**
     * @return WatchCountIncreaseFilter
     */
    public function getWatchCountIncreaseFilter(): WatchCountIncreaseFilter
    {
        return $this->watchCountIncreaseFilter;
    }
    /**
     * @return WatchCountDecreaseFilter
     */
    public function getWatchCountDecreaseFilter(): WatchCountDecreaseFilter
    {
        return $this->watchCountDecreaseFilter;
    }
    /**
     * @return HighestPriceFilter
     */
    public function getHighestPriceFilter(): HighestPriceFilter
    {
        return $this->highestPriceFilter;
    }
    /**
     * @return LowestPriceFilter
     */
    public function getLowestPriceFilter(): LowestPriceFilter
    {
        return $this->lowestPriceFilter;
    }
    /**
     * @return SearchQueryRegexFilter
     */
    public function getSearchQueryRegexFilter(): SearchQueryRegexFilter
    {
        return $this->searchQueryRegexFilter;
    }
    /**
     * @return FixedPriceFilter
     */
    public function getFixedPriceFilter(): FixedPriceFilter
    {
        return $this->fixedPriceFilter;
    }
}