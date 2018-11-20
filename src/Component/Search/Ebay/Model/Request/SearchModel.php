<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SearchModel implements ArrayNotationInterface
{
    /**
     * @var string $keyword
     */
    private $keyword;
    /**
     * @var Range $range
     */
    private $range;
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
     * @var iterable $marketplaces
     */
    private $marketplaces;
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
     * SearchModel constructor.
     * @param string $keyword
     * @param bool $lowestPrice
     * @param bool $highestPrice
     * @param bool $highQuality
     * @param bool $bestMatch
     * @param array $shippingCountries
     * @param array $marketplaces
     * @param array $taxonomies
     * @param Pagination $pagination
     * @param string $globalId
     * @param string $locale
     * @param Pagination $internalPagination
     * @param Range $range
     */
    public function __construct(
        string $keyword,
        bool $lowestPrice,
        bool $highestPrice,
        bool $highQuality,
        bool $bestMatch,
        array $shippingCountries,
        array $marketplaces,
        array $taxonomies,
        Pagination $pagination,
        string $globalId,
        string $locale,
        Pagination $internalPagination,
        Range $range
    ) {
        $this->keyword = $keyword;
        $this->lowestPrice = $lowestPrice;
        $this->highQuality = $highQuality;
        $this->highestPrice = $highestPrice;
        $this->bestMatch = $bestMatch;
        $this->shippingCountries = $shippingCountries;
        $this->marketplaces = $marketplaces;
        $this->taxonomies = $taxonomies;
        $this->pagination = $pagination;
        $this->globalId = $globalId;
        $this->locale = $locale;
        $this->internalPagination = $internalPagination;
        $this->range = $range;
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
     * @param bool $lowestPrice
     */
    public function setLowestPrice(bool $lowestPrice)
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
    public function getMarketplaces(): iterable
    {
        return $this->marketplaces;
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
     * @return Range
     */
    public function getRange(): Range
    {
        return $this->range;
    }
    /**
     * @param array $replacementData
     * @return string
     */
    public function getUniqueName(array $replacementData = []): string
    {
        return md5(serialize([
            'keyword' => (isset($replacementData['keyword'])) ? $replacementData['keyword'] : $this->getKeyword(),
            'lowestPrice' => (isset($replacementData['lowestPrice'])) ? $replacementData['lowestPrice'] : $this->isLowestPrice(),
            'bestMatch' => (isset($replacementData['bestMatch'])) ? $replacementData['bestMatch'] : $this->isBestMatch(),
            'highQuality' => (isset($replacementData['highQuality'])) ? $replacementData['highQuality'] : $this->isHighQuality(),
            'shippingCountries' => (isset($replacementData['shippingCountries'])) ? $replacementData['shippingCountries'] : $this->getShippingCountries(),
            'internalPagination' => (isset($replacementData['internalPagination']) and $replacementData['internalPagination'] instanceof Pagination) ? $replacementData['internalPagination']->toArray() : $this->getInternalPagination()->toArray(),
            'globalId' => $this->getGlobalId(),
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
        ];
    }
}