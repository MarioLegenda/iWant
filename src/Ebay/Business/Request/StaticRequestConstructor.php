<?php

namespace App\Ebay\Business\Request;

use App\Ebay\Presentation\Model\Query as EbayQuery;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Presentation\ShoppingApi\Model\GetSingleItem;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Ebay\Presentation\ShoppingApi\Model\GetShippingCosts;

class StaticRequestConstructor
{
    /**
     * @param string $itemId
     * @return ShoppingApiModel
     */
    public static function createEbaySingleItemRequest(string $itemId): ShoppingApiModel
    {
        $callname = new EbayQuery(
            'callname',
            'GetSingleItem'
        );

        $itemId = new EbayQuery(
            'ItemID',
            $itemId
        );

        $queries = TypedArray::create('integer', EbayQuery::class);

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
    /**
     * @param string $itemId
     * @param string $destinationCountryCode
     * @return ShoppingApiModel
     */
    public static function createEbayShippingCostsItemRequest(
        string $itemId,
        string $destinationCountryCode
    ): ShoppingApiModel {
        $callname = new EbayQuery(
            'callname',
            'GetShippingCosts'
        );

        $itemId = new EbayQuery(
            'ItemID',
            $itemId
        );

        $destinationCountryCode = new EbayQuery(
            'DestinationCountryCode',
            $destinationCountryCode
        );

        $includeDetails = new EbayQuery(
            'IncludeDetails',
            'true'
        );

        $queries = TypedArray::create('integer', EbayQuery::class);

        $queries[] = $callname;
        $queries[] = $itemId;
        $queries[] = $destinationCountryCode;
        $queries[] = $includeDetails;

        $getShippingCosts = new GetShippingCosts($queries);

        return new ShoppingApiModel($getShippingCosts);
    }
}