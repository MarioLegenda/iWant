<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\App\Library\Representation\SiteLocaleMappingRepresentation;
use App\Component\Search\Ebay\Business\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Business\Filter\SearchQueryRegexFilter;
use App\Component\Search\Ebay\Business\Filter\SortingMethod;
use App\Component\Search\Ebay\Business\FilterResolver;
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
     * @var FilterResolver $filterResolver
     */
    private $filterResolver;
    /**
     * @var SiteLocaleMappingRepresentation $siteLocaleMappingRepresentation
     */
    private $siteLocaleMappingRepresentation;
    /**
     * FetcherFactory constructor.
     * @param SingleSearchFetcher $singleResultFetcher
     * @param FilterApplierInterface $filterApplier
     * @param DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher
     * @param FilterResolver $filterResolver
     * @param SiteLocaleMappingRepresentation $siteLocaleMappingRepresentation
     */
    public function __construct(
        SingleSearchFetcher $singleResultFetcher,
        DoubleLocaleSearchFetcher $doubleLocaleSearchFetcher,
        FilterApplierInterface $filterApplier,
        FilterResolver $filterResolver,
        SiteLocaleMappingRepresentation $siteLocaleMappingRepresentation
    ) {
        $this->singleResultFetcher = $singleResultFetcher;
        $this->filterApplier = $filterApplier;
        $this->doubleLocaleSearchFetcher = $doubleLocaleSearchFetcher;
        $this->filterResolver = $filterResolver;
        $this->siteLocaleMappingRepresentation = $siteLocaleMappingRepresentation;
    }
    /**
     * @param SearchModel|SearchModelInterface $model
     * @return FetcherInterface
     */
    public function decideFetcher(SearchModelInterface $model): FetcherInterface
    {
        $chosenFetcher = ($model->isDoubleLocaleSearch()) ? $this->doubleLocaleSearchFetcher : $this->singleResultFetcher;

        if ($model->getSortingMethod() === SortingMethod::WATCH_COUNT_INCREASE) {
            $this->filterApplier->add($this->filterResolver->getWatchCountIncreaseFilter());
        }

        if ($model->getSortingMethod() === SortingMethod::WATCH_COUNT_DECREASE) {
            $this->filterApplier->add($this->filterResolver->getWatchCountDecreaseFilter());
        }

        if ($model->isSearchQueryFilter()) {
            $searchQueryRegexFilter = $this->filterResolver->getSearchQueryRegexFilter();

            $searchQueryRegexFilter->setLocale(
                $this->siteLocaleMappingRepresentation->getLocaleByGlobalId($model->getGlobalId())['locale']
            );

            $searchQueryRegexFilter->setSearchKeyword(
                $model->getKeyword()
            );

            $this->filterApplier->add(
                $searchQueryRegexFilter,
                1
            );
        }

        if ($model->isLowestPrice()) {
            $this->filterApplier->add(
                $this->filterResolver->getLowestPriceFilter(),
                3
            );
        }

        if ($model->isHighestPrice()) {
            $this->filterApplier->add(
                $this->filterResolver->getHighestPriceFilter()
                , 3
            );
        }

        if ($model->isFixedPriceOnly()) {
            $this->filterApplier->add(
                $this->filterResolver->getFixedPriceFilter(),
                2);
        }

        if ($this->filterApplier->hasFilters()) {
            $chosenFetcher->addFilterApplier($this->filterApplier);
        }

        return $chosenFetcher;
    }
}