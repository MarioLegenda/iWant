<?php

namespace App\Tests\Ebay\ShoppingApi\DataProvider;

use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\Model\GetCategoryInfo;
use App\Ebay\Presentation\ShoppingApi\Model\GetShippingCosts;
use App\Ebay\Presentation\ShoppingApi\Model\GetSingleItem;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class DataProvider
{
    /**
     * @param string $globalId
     * @return ShoppingApiRequestModelInterface
     */
    public function createGetCategoryInfoModel(string $globalId = GlobalIdInformation::EBAY_GB): ShoppingApiRequestModelInterface
    {
        $callname = new Query(
            'callname',
            'GetCategoryInfo'
        );

        $categoryId = new Query(
            'CategoryId',
            20081
        );

        $globalId = new Query(
            'GLOBAL-ID',
            $globalId
        );

        $includeSelector = new Query(
            'IncludeSelector',
            'ChildCategories'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $categoryId;
        $queries[] = $globalId;
        $queries[] = $callname;
        $queries[] = $includeSelector;

        $callType = new GetCategoryInfo($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        return new ShoppingApiModel($callType, $itemFilters);
    }
    /**
     * @return ShoppingApiModel
     */
    public function createGetSingleItemModel()
    {
        $callname = new Query(
            'callname',
            'GetSingleItem'
        );

        $itemId = new Query(
            'ItemID',
            '302230240168'
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
            ['Details', 'Description', 'TextDescription', 'ShippingCosts', 'ItemSpecifics', 'Variations', 'Compatibility']
        ));

        $itemFilters[] = $includeSelector;

        return new ShoppingApiModel($callType, $itemFilters);
    }

    /**
     * @return ShoppingApiModel
     */
    public function createGetShippingCostsModel()
    {
        $callname = new Query(
            'callname',
            'GetShippingCosts'
        );

        $destinationCountryCode = new Query(
            'DestinationCountryCode',
            'IE'
        );

        $itemId = new Query(
            'ItemID',
            '264037155079'
        );

        $includeDetails = new Query(
            'IncludeDetails',
            'true'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $callname;
        $queries[] = $destinationCountryCode;
        $queries[] = $itemId;
        $queries[] = $includeDetails;

        $callType = new GetShippingCosts($queries);

        return new ShoppingApiModel($callType);
    }
}