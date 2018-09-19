<?php

namespace App\Tests\App\DataProvider;

use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;

class DataProvider
{
    /**
     * @param string $itemId
     * @param MarketplaceType|TypeInterface $marketplace
     * @return SingleItemRequestModel
     */
    public function createSingleItemRequestModel(
        string $itemId,
        MarketplaceType $marketplace
    ): SingleItemRequestModel {
        return new SingleItemRequestModel(
            $marketplace,
            $itemId
        );
    }
}