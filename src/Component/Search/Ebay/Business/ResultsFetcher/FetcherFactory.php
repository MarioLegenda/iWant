<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\Filter\LowestPriceFilter;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class FetcherFactory
{
    /**
     * @var SingleResultFetcher $singleResultFetcher
     */
    private $singleResultFetcher;
    /**
     * @var FilterApplierInterface $filterApplier
     */
    private $filterApplier;
    /**
     * FetcherFactory constructor.
     * @param SingleResultFetcher $singleResultFetcher
     * @param FilterApplierInterface $filterApplier
     */
    public function __construct(
        SingleResultFetcher $singleResultFetcher,
        FilterApplierInterface $filterApplier
    ) {
        $this->singleResultFetcher = $singleResultFetcher;
        $this->filterApplier = $filterApplier;
    }
    /**
     * @param SearchModel $model
     * @return object
     */
    public function decideFetcher(SearchModel $model): object
    {
        if ($model->isLowestPrice()) {
            $this->filterApplier->add(new LowestPriceFilter());
        }

        if ($this->filterApplier->hasFilters()) {
            $this->singleResultFetcher->addFilterApplier($this->filterApplier);
        }

        return $this->singleResultFetcher;
    }
}