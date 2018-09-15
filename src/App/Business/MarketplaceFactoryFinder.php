<?php

namespace App\App\Business;

use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Library\MarketplaceType;
use App\Library\Representation\MarketplaceRepresentation;

class MarketplaceFactoryFinder
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var EtsyApiEntryPoint $etsyApiEntryPoint
     */
    private $etsyApiEntryPoint;
    /**
     * @var MarketplaceRepresentation $marketplaceRepresentation
     */
    private $marketplaceRepresentation;
    /**
     * MarketplaceFactoryFinder constructor.
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     * @param MarketplaceRepresentation $marketplaceRepresentation
     */
    public function __construct(
        MarketplaceRepresentation $marketplaceRepresentation,
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        EtsyApiEntryPoint $etsyApiEntryPoint
    ) {
        $this->marketplaceRepresentation = $marketplaceRepresentation;
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
    }
    /**
     * @param MarketplaceType $marketplace
     * @param string $itemId
     */
    public function getSingleItem(
        MarketplaceType $marketplace,
        string $itemId
    ) {
        if ($this->marketplaceRepresentation->ebay->equals($marketplace)) {

        } else if ($this->marketplaceRepresentation->etsy->equals($marketplace)) {

        }
    }
}