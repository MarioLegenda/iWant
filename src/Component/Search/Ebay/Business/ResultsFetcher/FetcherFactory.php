<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\Filter\FixedPriceFilter;
use App\Component\Search\Ebay\Business\Filter\HighestPriceFilter;
use App\Component\Search\Ebay\Business\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher\DoubleLocaleSearchFetcher;
use App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher\FetcherInterface;
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
     * @var DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher
     */
    private $doubleLocaleSearchFetcher;
    /**
     * FetcherFactory constructor.
     * @param SingleSearchFetcher $singleResultFetcher
     * @param FilterApplierInterface $filterApplier
     * @param DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher
     */
    public function __construct(
        SingleSearchFetcher $singleResultFetcher,
        DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher,
        FilterApplierInterface $filterApplier
    ) {
        $this->singleResultFetcher = $singleResultFetcher;
        $this->filterApplier = $filterApplier;
        $this->doubleLocaleSearchFetcher = $doubleLocaleSearchFetcher;
    }
    /**
     * @param SearchModel|SearchModelInterface $model
     * @return FetcherInterface
     */
    public function decideFetcher(SearchModelInterface $model): FetcherInterface
    {
        $chosenFetcher = ($model->isDoubleLocaleSearch()) ? $this->doubleLocaleSearchFetcher : $this->singleResultFetcher;

        if ($model->isLowestPrice()) {
            $this->filterApplier->add(new LowestPriceFilter(), 2);
        }

        if ($model->isHighestPrice()) {
            $this->filterApplier->add(new HighestPriceFilter(), 1);
        }

        if ($model->isFixedPriceOnly()) {
            $this->filterApplier->add(new FixedPriceFilter(), 1);
        }

        if ($this->filterApplier->hasFilters()) {
            $chosenFetcher->addFilterApplier($this->filterApplier);
        }

        return $chosenFetcher;
    }
}