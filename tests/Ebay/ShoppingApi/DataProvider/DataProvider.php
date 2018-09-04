<?php

namespace App\Tests\Ebay\ShoppingApi\DataProvider;

use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\Model\GetCategoryInfo;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;

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
}