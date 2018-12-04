<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\Filter\HighestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher\SingleSearchFetcher;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;

class FetcherFactory
{
    /**
     * @var SingleSearchFetcher $singleResultFetcher
     */
    private $singleResultFetcher;
    /**
     * @var FilterApplierInterface $filterApplier
     */
    private $filterApplier;
    /**
     * FetcherFactory constructor.
     * @param SingleSearchFetcher $singleResultFetcher
     * @param FilterApplierInterface $filterApplier
     */
    public function __construct(
        SingleSearchFetcher $singleResultFetcher,
        FilterApplierInterface $filterApplier
    ) {
        $this->singleResultFetcher = $singleResultFetcher;
        $this->filterApplier = $filterApplier;
    }
    /**
     * @param SearchModel|SearchModelInterface $model
     * @return object
     */
    public function decideFetcher(SearchModelInterface $model): object
    {
        if ($model->isLowestPrice()) {
            $this->filterApplier->add(new LowestPriceFilter());
        }

        if ($model->isHighestPrice()) {
            $this->filterApplier->add(new HighestPriceFilter());
        }

        if ($this->filterApplier->hasFilters()) {
            $this->singleResultFetcher->addFilterApplier($this->filterApplier);
        }

        return $this->singleResultFetcher;
    }
}