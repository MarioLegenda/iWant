<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\Filter\FixedPriceFilter;
use App\Component\Search\Ebay\Business\Filter\HighestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\SearchQueryRegexFilter;

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
     * FilterResolver constructor.
     * @param HighestPriceFilter $highestPriceFilter
     * @param LowestPriceFilter $lowestPriceFilter
     * @param SearchQueryRegexFilter $searchQueryRegexFilter
     * @param FixedPriceFilter $fixedPriceFilter
     */
    public function __construct(
        HighestPriceFilter $highestPriceFilter,
        LowestPriceFilter $lowestPriceFilter,
        SearchQueryRegexFilter $searchQueryRegexFilter,
        FixedPriceFilter $fixedPriceFilter
    ) {
        $this->highestPriceFilter = $highestPriceFilter;
        $this->lowestPriceFilter = $lowestPriceFilter;
        $this->searchQueryRegexFilter = $searchQueryRegexFilter;
        $this->fixedPriceFilter = $fixedPriceFilter;
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