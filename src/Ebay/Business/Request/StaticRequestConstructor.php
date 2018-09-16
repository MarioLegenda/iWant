<?php

namespace App\Ebay\Business\Request;

use App\App\Presentation\Model\SingleItemRequestModel;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Presentation\ShoppingApi\Model\GetSingleItem;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;

class StaticRequestConstructor
{
    /**
     * @param string $itemId
     * @return ShoppingApiModel
     */
    public static function createSingleItemRequest(string $itemId): ShoppingApiModel
    {
        $callname = new Query(
            'callname',
            'GetSingleItem'
        );

        $itemId = new Query(
            'ItemID',
            $itemId
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $callname;
        $queries[] = $itemId;

        $callType = new GetSingleItem($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        $includeSelector = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::INCLUDE_SELECTOR,
            ['Details', 'Description', 'TextDescription', 'ShippingCosts', 'ItemSpecifics']
        ));

        $itemFilters[] = $includeSelector;

        return new ShoppingApiModel($callType, $itemFilters);
    }
}