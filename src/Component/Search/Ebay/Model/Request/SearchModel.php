<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Web\Library\View\EbaySearchViewType;

class SearchModel implements ArrayNotationInterface
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
     * @var string $viewType
     */
    private $viewType;
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
     * @param EbaySearchViewType|TypeInterface $viewType
     * @param string $globalId
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
        EbaySearchViewType $viewType,
        string $globalId
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
        $this->viewType = $viewType;
        $this->globalId = $globalId;
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
     * @return string
     */
    public function getViewType(): string
    {
        return $this->viewType;
    }
    /**
     * @return bool
     */
    public function isBestMatch(): bool
    {
        return $this->bestMatch;
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
            'marketplaces' => $this->getMarketplaces(),
            'taxonomies' => $this->getTaxonomies(),
            'pagination' => $this->getPagination()->toArray(),
            'globalId' => $this->getGlobalId(),
            'viewType' => $this->getViewType(),
        ];
    }
}