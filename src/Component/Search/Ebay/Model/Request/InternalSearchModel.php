<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Translation\Model\Language;

class InternalSearchModel implements SearchModelInterface, ArrayNotationInterface
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
    private $searchQueryFilter;
    /**
     * @var bool
     */
    private $watchCount;
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
     * @param bool $isSearchStores
     * @param string $sortingMethod
     * @param bool $searchQueryFilter
     * @param bool $watchCount
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
        bool $isSearchStores,
        string $sortingMethod,
        bool $searchQueryFilter,
        bool $watchCount
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
        $this->searchStores = $isSearchStores;
        $this->sortingMethod = $sortingMethod;
        $this->searchQueryFilter = $searchQueryFilter;
        $this->watchCount = $watchCount;
    }
    /**
     * @param string $sortingMethod
     */
    public function setSortingMethod(string $sortingMethod): void
    {
        $this->sortingMethod = $sortingMethod;
    }
    /**
     * @param bool $searchQueryFilter
     */
    public function setSearchQueryFilter(bool $searchQueryFilter): void
    {
        $this->searchQueryFilter = $searchQueryFilter;
    }
    /**
     * @param bool $watchCount
     */
    public function setWatchCount(bool $watchCount): void
    {
        $this->watchCount = $watchCount;
    }
    /**
     * @return bool
     */
    public function isWatchCount(): bool
    {
        return $this->watchCount;
    }
    /**
     * @return Language
     */
    public function getKeyword(): Language
    {
        return $this->keyword;
    }
    /**
     * @param Language $keyword
     */
    public function setKeyword(Language $keyword): void
    {
        $this->keyword = $keyword;
    }
    /**
     * @return bool
     */
    public function isLowestPrice(): bool
    {
        return $this->lowestPrice;
    }
    /**
     * @param bool $lowestPrice
     */
    public function setLowestPrice(bool $lowestPrice): void
    {
        $this->lowestPrice = $lowestPrice;
    }
    /**
     * @return bool
     */
    public function isHighestPrice(): bool
    {
        return $this->highestPrice;
    }
    /**
     * @param bool $highestPrice
     */
    public function setHighestPrice(bool $highestPrice): void
    {
        $this->highestPrice = $highestPrice;
    }
    /**
     * @return bool
     */
    public function isBestMatch(): bool
    {
        return $this->sortingMethod === 'bestMatch';
    }
    /**
     * @return bool
     */
    public function isHighQuality(): bool
    {
        return $this->highQuality;
    }
    /**
     * @param bool $highQuality
     */
    public function setHighQuality(bool $highQuality): void
    {

        $this->highQuality = $highQuality;
    }
    /**
     * @return iterable
     */
    public function getShippingCountries(): iterable
    {
        return $this->shippingCountries;
    }
    /**
     * @param iterable $shippingCountries
     */
    public function setShippingCountries(iterable $shippingCountries): void
    {
        $this->shippingCountries = $shippingCountries;
    }
    /**
     * @return iterable
     */
    public function getTaxonomies(): iterable
    {
        return $this->taxonomies;
    }
    /**
     * @param iterable $taxonomies
     */
    public function setTaxonomies(iterable $taxonomies): void
    {
        $this->taxonomies = $taxonomies;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @param string $globalId
     */
    public function setGlobalId(string $globalId): void
    {
        $this->globalId = $globalId;
    }
    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
    /**
     * @param Pagination $pagination
     */
    public function setPagination(Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
    /**
     * @return Pagination
     */
    public function getInternalPagination(): Pagination
    {
        return $this->internalPagination;
    }
    /**
     * @param Pagination $internalPagination
     */
    public function setInternalPagination(Pagination $internalPagination): void
    {
        $this->internalPagination = $internalPagination;
    }
    /**
     * @return bool
     */
    public function isDoubleLocaleSearch(): bool
    {
        return $this->doubleLocaleSearch;
    }
    /**
     * @param bool $doubleLocaleSearch
     */
    public function setDoubleLocaleSearch(bool $doubleLocaleSearch): void
    {
        $this->doubleLocaleSearch = $doubleLocaleSearch;
    }
    /**
     * @return bool
     */
    public function isHideDuplicateItems(): bool
    {
        return $this->hideDuplicateItems;
    }
    /**
     * @param bool $hideDuplicateItems
     */
    public function setHideDuplicateItems(bool $hideDuplicateItems): void
    {
        $this->hideDuplicateItems = $hideDuplicateItems;
    }
    /**
     * @return bool
     */
    public function isFixedPriceOnly(): bool
    {
        return $this->fixedPriceOnly;
    }
    /**
     * @param bool $fixedPriceOnly
     */
    public function setFixedPriceOnly(bool $fixedPriceOnly): void
    {
        $this->fixedPriceOnly = $fixedPriceOnly;
    }
    /**
     * @return bool
     */
    public function isSearchStores(): bool
    {
        return $this->searchStores;
    }
    /**
     * @param bool $searchStores
     */
    public function setSearchStores(bool $searchStores): void
    {
        $this->searchStores = $searchStores;
    }
    /**
     * @return bool
     */
    public function isNewlyListed(): bool
    {
        return $this->sortingMethod === 'newlyListed';
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
}