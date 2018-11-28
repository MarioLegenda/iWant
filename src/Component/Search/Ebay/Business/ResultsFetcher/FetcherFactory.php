<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\HighestPriceFilter;

class FetcherFactory
{
    /**
     * @var SourceUnFilteredFetcher $sourceUnfilteredFetcher
     */
    private $sourceUnFilteredFetcher;
    /**
     * @var LowestPriceFilter $lowestPriceFilter
     */
    private $lowestPriceFilter;
    /**
     * @var HighestPriceFilter $highestPriceFilter
     */
    private $highestPriceFilter;
    /**
     * @var FilterApplierInterface $filterApplier
     */
    private $filterApplier;
    /**
     * FetcherFactory constructor.
     * @param SourceUnFilteredFetcher $sourceUnFilteredFetcher
     * @param LowestPriceFilter $lowestPriceFilter
     * @param HighestPriceFilter $highestPriceFilter
     * @param FilterApplierInterface $filterApplier
     */
    public function __construct(
        SourceUnFilteredFetcher $sourceUnFilteredFetcher,
        LowestPriceFilter $lowestPriceFilter,
        HighestPriceFilter $highestPriceFilter,
        FilterApplierInterface $filterApplier
    ) {
        $this->sourceUnFilteredFetcher = $sourceUnFilteredFetcher;
        $this->lowestPriceFilter = $lowestPriceFilter;
        $this->highestPriceFilter = $highestPriceFilter;
        $this->filterApplier = $filterApplier;
    }
    /**
     * @param SearchModel $model
     * @return object
     */
    public function decideFetcher(SearchModel $model): object
    {
        if ($model->isHighestPrice()) {
            $this->filterApplier->add($this->highestPriceFilter, 100);
        }

        if ($model->isLowestPrice()) {
            $this->filterApplier->add($this->lowestPriceFilter, 100);
        }

        if ($this->filterApplier->hasFilters()) {
            $this->sourceUnFilteredFetcher->addFilterApplier($this->filterApplier);
        }

        return $this->sourceUnFilteredFetcher;
    }
}