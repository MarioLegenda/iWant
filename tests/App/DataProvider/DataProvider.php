<?php

namespace App\Tests\App\DataProvider;

use App\App\Presentation\Model\SingleItemRequestModel;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\MarketplaceType;

class DataProvider
{
    /**
     * @param string $itemId
     * @return SingleItemRequestModel
     */
    public function createSingleItemRequestModel(string $itemId): SingleItemRequestModel
    {
        return new SingleItemRequestModel(
            MarketplaceType::fromValue('Ebay'),
            $itemId
        );
    }
}