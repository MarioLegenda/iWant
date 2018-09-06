<?php

namespace App\Library\Representation;

use App\Library\MarketplaceType;

class MarketplaceRepresentation
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
     * @var MarketplaceType $viagogo
     */
    public $viagogo;
    /**
     * @var MarketplaceType $ticketMaster
     */
    public $ticketMaster;
    /**
     * MarketplaceRepresentation constructor.
     * @param iterable $marketplaces
     */
    public function __construct(
        iterable $marketplaces
    ) {
        foreach ($marketplaces as $key => $marketplace) {
            $this->{$key} = MarketplaceType::fromValue($marketplace);
        }
    }
}