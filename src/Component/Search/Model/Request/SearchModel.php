<?php

namespace App\Component\Search\Model\Request;

class SearchModel
{
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
     * SearchModel constructor.
     * @param bool $lowestPrice
     * @param bool $highestPrice
     * @param bool $highQuality
     * @param array $shippingCountries
     * @param array $marketplaces
     * @param array $taxonomies
     */
    public function __construct(
        bool $lowestPrice,
        bool $highestPrice,
        bool $highQuality,
        array $shippingCountries,
        array $marketplaces,
        array $taxonomies
    ) {
        $this->lowestPrice = $lowestPrice;
        $this->highQuality = $highQuality;
        $this->highestPrice = $highestPrice;
        $this->shippingCountries = $shippingCountries;
        $this->marketplaces = $marketplaces;
        $this->taxonomies = $taxonomies;
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
}