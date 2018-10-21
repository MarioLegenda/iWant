<?php

namespace App\Library\Representation;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\MarketplaceType;

class MarketplaceRepresentation implements ArrayNotationInterface, \IteratorAggregate
{
    /**
     * @var MarketplaceType $ebay
     */
    public $ebay;
    /**
     * @var MarketplaceType $etsy
     */
    public $etsy;
    /**
     * @var MarketplaceType $amazon
     */
    public $amazon;
    /**
     * @var iterable $marketplaces
     */
    public $marketplaces;
    /**
     * MarketplaceRepresentation constructor.
     * @param iterable $marketplaces
     */
    public function __construct(
        iterable $marketplaces
    ) {
        foreach ($marketplaces as $key => $marketplace) {
            $marketplaceType = MarketplaceType::fromValue($marketplace);

            $this->{$key} = $marketplaceType;

            $this->marketplaces[$key] = $marketplaceType;
        }
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->toArray());
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return $this->marketplaces;
    }
}