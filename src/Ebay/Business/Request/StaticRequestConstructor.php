<?php

namespace App\Ebay\Business\Request;

use App\Ebay\Presentation\Model\Query as EbayQuery;
use App\Etsy\Presentation\Model\Query as EtsyQuery;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Presentation\ShoppingApi\Model\GetSingleItem;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Etsy\Presentation\Model\ItemFilterModel;

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
     * @param string $listingId
     * @return EtsyApiModel
     */
    public static function createEtsySingleItemRequest(string $listingId)
    {
        $methodType = MethodType::fromKey('getListing');

        $queries = TypedArray::create('integer', EtsyQuery::class);

        $listingIdQuery = new EtsyQuery(sprintf('/listings/%s?', $listingId));

        $queries[] = $listingIdQuery;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $model = new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );

        return $model;
    }
}