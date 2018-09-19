<?php

namespace App\App\Presentation\Model\Request;

use App\Library\Infrastructure\Type\TypeInterface;
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
     * @param MarketplaceType|TypeInterface $marketplace
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