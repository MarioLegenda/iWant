<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Translation\Model\Language;

class SearchModel implements SearchModelInterface, ArrayNotationInterface
{
    /**
     * @var string $keyword
     */
    private $keyword;
    /**
     * @var bool $lowestPrice
     */
    private $lowestPrice;
    /**
     * @var bool $highestPrice
     */
    private $highestPrice;
    /**
     * @var bool $highQuality
     */
    private $highQuality;
    /**
     * @var iterable $shippingCountries
     */
    private $shippingCountries;
    /**
     * @var iterable $taxonomies
     */
    private $taxonomies;
    /**
     * @var string $globalId
     */
    private $globalId;
    /**
     * @var Pagination $pagination
     */
    private $pagination;
    /**
     * @var string $locale
     */
    private $locale;
    /**
     * @var Pagination $internalPagination
     */
    private $internalPagination;
    /**
     * @var bool $hideDuplicateItems
     */
    private $hideDuplicateItems = false;
    /**
     * @var bool $doubleLocaleSearch
     */
    private $doubleLocaleSearch = false;
    /**
     * @var bool $fixedPriceOnly
     */
    private $fixedPriceOnly = false;
    /**
     * @var bool $searchStores
     */
    private $searchStores = false;
    /**
     * @var string $sortingMethod
     */
    private $sortingMethod = 'bestMatch';
    /**
     * @var bool $searchQueryFilter
     */
    private $searchQueryFilter = false;
    /**
     * SearchModel constructor.
     * @param Language $keyword
     * @param bool $lowestPrice
     * @param bool $highestPrice
     * @param bool $highQuality
     * @param array $shippingCountries
     * @param array $taxonomies
     * @param Pagination $pagination
     * @param string $globalId
     * @param string $locale
     * @param Pagination $internalPagination
     * @param bool $hideDuplicateItems
     * @param bool $doubleLocaleSearch
     * @param bool $fixedPriceOnly
     * @param bool $searchStores
     * @param string $sortingMethod
     * @param bool $searchQueryFilter
     */
    public function __construct(
        Language $keyword,
        bool $lowestPrice,
        bool $highestPrice,
        bool $highQuality,
        array $shippingCountries,
        array $taxonomies,
        Pagination $pagination,
        string $globalId,
        string $locale,
        Pagination $internalPagination,
        bool $hideDuplicateItems,
        bool $doubleLocaleSearch,
        bool $fixedPriceOnly,
        bool $searchStores,
        string $sortingMethod,
        bool $searchQueryFilter
    ) {
        $this->keyword = $keyword;
        $this->lowestPrice = $lowestPrice;
        $this->highQuality = $highQuality;
        $this->highestPrice = $highestPrice;
        $this->shippingCountries = $shippingCountries;
        $this->taxonomies = $taxonomies;
        $this->pagination = $pagination;
        $this->globalId = $globalId;
        $this->locale = $locale;
        $this->internalPagination = $internalPagination;
        $this->hideDuplicateItems = $hideDuplicateItems;
        $this->doubleLocaleSearch = $doubleLocaleSearch;
        $this->fixedPriceOnly = $fixedPriceOnly;
        $this->searchStores = $searchStores;
        $this->sortingMethod = $sortingMethod;
        $this->searchQueryFilter = $searchQueryFilter;
    }
    /**
     * @return Language
     */
    public function getKeyword(): Language
    {
        return $this->keyword;
    }
    /**
     * @return bool
     */
    public function isSearchStores(): bool
    {
        return $this->searchStores;
    }
    /**
     * @return bool
     */
    public function isLowestPrice(): bool
    {
        return $this->lowestPrice;
    }
    /**
     * @return bool
     */
    public function isNewlyListed(): bool
    {
        return $this->sortingMethod === 'newlyListed';
    }
    /**
     * @return bool
     */
    public function isHighestPrice(): bool
    {
        return $this->highestPrice;
    }
    /**
     * @return bool
     */
    public function isHighQuality(): bool
    {
        return $this->highQuality;
    }
    /**
     * @return iterable
     */
    public function getShippingCountries(): iterable
    {
        return $this->shippingCountries;
    }
    /**
     * @return iterable
     */
    public function getTaxonomies(): iterable
    {
        return $this->taxonomies;
    }
    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return bool
     */
    public function isBestMatch(): bool
    {
        return $this->sortingMethod === 'bestMatch';
    }
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    /**
     * @return Pagination
     */
    public function getInternalPagination(): Pagination
    {
        return $this->internalPagination;
    }
    /**
     * @return bool
     */
    public function isHideDuplicateItems(): bool
    {
        return $this->hideDuplicateItems;
    }
    /**
     * @return bool
     */
    public function isDoubleLocaleSearch(): bool
    {
        return $this->doubleLocaleSearch;
    }
    /**
     * @return bool
     */
    public function isFixedPriceOnly(): bool
    {
        return $this->fixedPriceOnly;
    }
    /**
     * @return string
     */
    public function getSortingMethod(): string
    {
        return $this->sortingMethod;
    }
    /**
     * @return bool
     */
    public function isSearchQueryFilter(): bool
    {
        return $this->searchQueryFilter;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'keyword' => $this->getKeyword(),
            'lowestPrice' => $this->isLowestPrice(),
            'highestPrice' => $this->isHighestPrice(),
            'highQuality' => $this->isHighQuality(),
            'shippingCountries' => $this->getShippingCountries(),
            'taxonomies' => $this->getTaxonomies(),
            'pagination' => $this->getPagination()->toArray(),
            'internalPagination' => $this->getInternalPagination()->toArray(),
            'globalId' => $this->getGlobalId(),
            'locale' => $this->getLocale(),
            'hideDuplicateItems' => $this->isHideDuplicateItems(),
            'isDoubleSearchLocale' => $this->isDoubleLocaleSearch(),
            'fixedPriceOnly' => $this->isFixedPriceOnly(),
            'searchStores' => $this->isSearchStores(),
            'sortingMethod' => $this->getSortingMethod(),
            'searchQueryFilter' => $this->isSearchQueryFilter(),
        ];
    }
    /**
     * @param SearchModel $model
     * @return SearchModelInterface
     */
    public static function createInternalSearchModelFromSearchModel(
        SearchModel $model
    ): SearchModelInterface {
        $keyword = $model->getKeyword();
        $lowestPrice = $model->isLowestPrice();
        $highestPrice = $model->isHighestPrice();
        $highQuality = $model->isHighQuality();
        $shippingCountries = $model->getShippingCountries();
        $taxonomies = $model->getTaxonomies();
        $globalId = $model->getGlobalId();
        $pagination = $model->getPagination();
        $locale = $model->getLocale();
        $internalPagination = $model->getInternalPagination();
        $hideDuplicateItems = $model->isHideDuplicateItems();
        $doubleLocaleSearch = $model->isDoubleLocaleSearch();
        $fixedPriceOnly = $model->isFixedPriceOnly();
        $searchStores = $model->isSearchStores();
        $sortingMethod = $model->getSortingMethod();
        $searchQueryFilter = $model->isSearchQueryFilter();

        return new InternalSearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $shippingCountries,
            $taxonomies,
            $pagination,
            $globalId,
            $locale,
            $internalPagination,
            $hideDuplicateItems,
            $doubleLocaleSearch,
            $fixedPriceOnly,
            $searchStores,
            $sortingMethod,
            $searchQueryFilter
        );
    }
}