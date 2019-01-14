<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\Filter\FixedPriceFilter;
use App\Component\Search\Ebay\Business\Filter\HighestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\SearchQueryRegexFilter;
use App\Component\Search\Ebay\Business\Filter\WatchCountFilter;

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
     * @var WatchCountFilter $watchCountFilter
     */
    private $watchCountFilter;
    /**
     * FilterResolver constructor.
     * @param HighestPriceFilter $highestPriceFilter
     * @param LowestPriceFilter $lowestPriceFilter
     * @param SearchQueryRegexFilter $searchQueryRegexFilter
     * @param FixedPriceFilter $fixedPriceFilter
     * @param WatchCountFilter $watchCountFilter
     */
    public function __construct(
        HighestPriceFilter $highestPriceFilter,
        LowestPriceFilter $lowestPriceFilter,
        SearchQueryRegexFilter $searchQueryRegexFilter,
        FixedPriceFilter $fixedPriceFilter,
        WatchCountFilter $watchCountFilter
    ) {
        $this->highestPriceFilter = $highestPriceFilter;
        $this->lowestPriceFilter = $lowestPriceFilter;
        $this->searchQueryRegexFilter = $searchQueryRegexFilter;
        $this->fixedPriceFilter = $fixedPriceFilter;
        $this->watchCountFilter = $watchCountFilter;
    }
    /**
     * @return WatchCountFilter
     */
    public function getWatchCountFilter(): WatchCountFilter
    {
        return $this->watchCountFilter;
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