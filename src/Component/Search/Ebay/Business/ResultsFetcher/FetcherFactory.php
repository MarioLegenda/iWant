<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher\DoubleLocaleSearchFetcher;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\HighestPriceFilter;
use App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher\SingleSearchFetcher;

class FetcherFactory
{
    /**
     * @var SingleSearchFetcher $singleSearchFetcher
     */
    private $singleSearchFetcher;
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
     * @var DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher
     */
    private $doubleLocaleSearchFetcher;
    /**
     * FetcherFactory constructor.
     * @param SingleSearchFetcher $singleSearchFetcher
     * @param DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher
     * @param LowestPriceFilter $lowestPriceFilter
     * @param HighestPriceFilter $highestPriceFilter
     * @param FilterApplierInterface $filterApplier
     */
    public function __construct(
        SingleSearchFetcher $singleSearchFetcher,
        DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher,
        LowestPriceFilter $lowestPriceFilter,
        HighestPriceFilter $highestPriceFilter,
        FilterApplierInterface $filterApplier
    ) {
        $this->singleSearchFetcher = $singleSearchFetcher;
        $this->lowestPriceFilter = $lowestPriceFilter;
        $this->highestPriceFilter = $highestPriceFilter;
        $this->filterApplier = $filterApplier;
        $this->doubleLocaleSearchFetcher = $doubleLocaleSearchFetcher;
    }
    /**
     * @param SearchModel $model
     * @return object
     */
    public function decideFetcher(SearchModel $model): object
    {
        $decidedFetcher = ($model->isDoubleLocaleSearch()) ? $this->doubleLocaleSearchFetcher : $this->singleSearchFetcher;

        if ($model->isHighestPrice()) {
            $this->filterApplier->add($this->highestPriceFilter, 100);
        }

        if ($model->isLowestPrice()) {
            $this->filterApplier->add($this->lowestPriceFilter, 100);
        }

        if ($this->filterApplier->hasFilters()) {
            $this->singleSearchFetcher->addFilterApplier($this->filterApplier);
        }

        return $decidedFetcher;
    }
}