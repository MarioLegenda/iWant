<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

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
     * @var boolean $bestMatch
     */
    private $bestMatch;
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
     * SearchModel constructor.
     * @param string $keyword
     * @param bool $lowestPrice
     * @param bool $highestPrice
     * @param bool $highQuality
     * @param bool $bestMatch
     * @param array $shippingCountries
     * @param array $taxonomies
     * @param Pagination $pagination
     * @param string $globalId
     * @param string $locale
     * @param Pagination $internalPagination
     * @param bool $hideDuplicateItems
     * @param bool $doubleLocaleSearch
     */
    public function __construct(
        string $keyword,
        bool $lowestPrice,
        bool $highestPrice,
        bool $highQuality,
        bool $bestMatch,
        array $shippingCountries,
        array $taxonomies,
        Pagination $pagination,
        string $globalId,
        string $locale,
        Pagination $internalPagination,
        bool $hideDuplicateItems,
        bool $doubleLocaleSearch
    ) {
        $this->keyword = $keyword;
        $this->lowestPrice = $lowestPrice;
        $this->highQuality = $highQuality;
        $this->highestPrice = $highestPrice;
        $this->bestMatch = $bestMatch;
        $this->shippingCountries = $shippingCountries;
        $this->taxonomies = $taxonomies;
        $this->pagination = $pagination;
        $this->globalId = $globalId;
        $this->locale = $locale;
        $this->internalPagination = $internalPagination;
        $this->hideDuplicateItems = $hideDuplicateItems;
        $this->doubleLocaleSearch = $doubleLocaleSearch;
    }
    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
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
        return $this->bestMatch;
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'keyword' => $this->getKeyword(),
            'bestMatch' => $this->isBestMatch(),
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
        ];
    }

    public static function createInternalSearchModelFromSearchModel(SearchModel $model): SearchModelInterface
    {
        $keyword = $model->getKeyword();
        $lowestPrice = $model->isLowestPrice();
        $highestPrice = $model->isHighestPrice();
        $bestMatch = $model->isBestMatch();
        $highQuality = $model->isHighQuality();
        $shippingCountries = $model->getShippingCountries();
        $taxonomies = $model->getTaxonomies();
        $globalId = $model->getGlobalId();
        $pagination = $model->getPagination();
        $locale = $model->getLocale();
        $internalPagination = $model->getInternalPagination();
        $hideDuplicateItems = $model->isHideDuplicateItems();
        $doubleLocaleSearch = $model->isDoubleLocaleSearch();

        return new InternalSearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $bestMatch,
            $shippingCountries,
            $taxonomies,
            $pagination,
            $globalId,
            $locale,
            $internalPagination,
            $hideDuplicateItems,
            $doubleLocaleSearch
        );
    }
}