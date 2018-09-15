<?php

namespace App\App\Presentation\Model;

use App\Library\MarketplaceType;

class SingleItemRequestModel
{
    /**
     * @var MarketplaceType $marketplace
     */
    private $marketplace;
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * SingleItemRequestModel constructor.
     * @param MarketplaceType $marketplace
     * @param string $itemId
     */
    public function __construct(
        MarketplaceType $marketplace,
        string $itemId
    ) {
        $this->marketplace = $marketplace;
        $this->itemId = $itemId;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return MarketplaceType
     */
    public function getMarketplace(): MarketplaceType
    {
        return $this->marketplace;
    }
}