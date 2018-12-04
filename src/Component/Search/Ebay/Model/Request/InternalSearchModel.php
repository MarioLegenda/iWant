<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

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
     * @param string $keyword
     */
    public function setKeyword(string $keyword): void
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
        return $this->bestMatch;
    }
    /**
     * @param bool $bestMatch
     */
    public function setBestMatch(bool $bestMatch): void
    {
        $this->bestMatch = $bestMatch;
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
     * @param array $replacementData
     * @return string
     */
    public function getUniqueName(array $replacementData = []): string
    {
        return md5(serialize([
            'keyword' => (isset($replacementData['keyword'])) ? $replacementData['keyword'] : $this->getKeyword(),
            'bestMatch' => (isset($replacementData['bestMatch'])) ? $replacementData['bestMatch'] : $this->isBestMatch(),
            'highQuality' => (isset($replacementData['highQuality'])) ? $replacementData['highQuality'] : $this->isHighQuality(),
            'shippingCountries' => (isset($replacementData['shippingCountries'])) ? $replacementData['shippingCountries'] : $this->getShippingCountries(),
            'internalPagination' => (isset($replacementData['internalPagination']) and $replacementData['internalPagination'] instanceof Pagination) ? $replacementData['internalPagination']->toArray() : $this->getInternalPagination()->toArray(),
            'page' => $this->getInternalPagination()->getPage(),
            'globalId' => $this->getGlobalId(),
            'hideDuplicateItems' => (isset($replacementData['hideDuplicateItems'])) ? $replacementData['hideDuplicateItems'] : $this->isHideDuplicateItems(),
        ]));
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
}