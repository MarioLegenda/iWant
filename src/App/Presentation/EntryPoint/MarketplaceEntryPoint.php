<?php

namespace App\App\Presentation\EntryPoint;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Representation\MarketplaceRepresentation;
use App\Library\Util\TypedRecursion;

class MarketplaceEntryPoint
{
    /**
     * @var MarketplaceRepresentation $marketplaceRepresentation
     */
    private $marketplaceRepresentation;
    /**
     * MarketplaceEntryPoint constructor.
     * @param MarketplaceRepresentation $marketplaceRepresentation
     */
    public function __construct(
        MarketplaceRepresentation $marketplaceRepresentation
    ) {
        $this->marketplaceRepresentation = $marketplaceRepresentation;
    }
    /**
     * @return TypedArray
     */
    public function getMarketplaces(): TypedArray
    {
        $marketplaces = TypedArray::create('string', 'string');
        /**
         * @var string $normalizedName
         * @var MarketplaceType $marketplace
         */
        foreach ($this->marketplaceRepresentation as $normalizedName => $marketplace) {
            $marketplaces[$normalizedName] = (string) $marketplace;
        }

        return $marketplaces;
    }
}