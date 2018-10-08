<?php

namespace App\Component\Search\Ebay\Model\Request;

class SearchModel
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
     * @var iterable $marketplaces
     */
    private $marketplaces;
    /**
     * @var iterable $taxonomies
     */
    private $taxonomies;
    /**
     * @var Pagination $pagination
     */
    private $pagination;
    /**
     * SearchModel constructor.
     * @param string $keyword
     * @param bool $lowestPrice
     * @param bool $highestPrice
     * @param bool $highQuality
     * @param array $shippingCountries
     * @param array $marketplaces
     * @param array $taxonomies
     * @param Pagination $pagination
     */
    public function __construct(
        string $keyword,
        bool $lowestPrice,
        bool $highestPrice,
        bool $highQuality,
        array $shippingCountries,
        array $marketplaces,
        array $taxonomies,
        Pagination $pagination
    ) {
        $this->keyword = $keyword;
        $this->lowestPrice = $lowestPrice;
        $this->highQuality = $highQuality;
        $this->highestPrice = $highestPrice;
        $this->shippingCountries = $shippingCountries;
        $this->marketplaces = $marketplaces;
        $this->taxonomies = $taxonomies;
        $this->pagination = $pagination;
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
}