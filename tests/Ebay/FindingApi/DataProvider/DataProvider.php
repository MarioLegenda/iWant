<?php

namespace App\Tests\Ebay\FindingApi\DataProvider;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsAdvanced;
use App\Ebay\Presentation\FindingApi\Model\FindItemsByKeywords;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;

class DataProvider
{
    public function getFindItemsByKeywordsData(string $keywords): FindingApiRequestModelInterface
    {
        $query = new Query(
            'keywords',
            $keywords
        );

        $queries = TypedArray::create('integer', Query::class);
        $queries[] = $query;

        $findItemsByKeywords = new FindItemsByKeywords($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        $maxPrice = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'MaxPrice',
            [56.7]
        ));

        $freeShippingOnly = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'FreeShippingOnly',
            [true]
        ));

        $listingType = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'ListingType',
            ['AuctionWithBIN', 'FixedPrice']
        ));

        $itemFilters[] = $maxPrice;
        $itemFilters[] = $freeShippingOnly;
        $itemFilters[] = $listingType;

        $model = new FindingApiModel($findItemsByKeywords, $itemFilters);

        return $model;
    }
    /**
     * @param string $keywords
     * @param int $categoryId
     * @return FindingApiRequestModelInterface
     */
    public function getFindItemsAdvanced(
        string $keywords,
        int $categoryId = null
    ): FindingApiRequestModelInterface {
        $keywordsQuery = new Query(
            'keywords',
            $keywords
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $keywordsQuery;

        $findItemsAdvanced = new FindItemsAdvanced($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        $maxPrice = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'MaxPrice',
            [56.7]
        ));

        $freeShippingOnly = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'FreeShippingOnly',
            [true]
        ));

        $listingType = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'ListingType',
            ['AuctionWithBIN', 'FixedPrice']
        ));

        $itemFilters[] = $maxPrice;
        $itemFilters[] = $freeShippingOnly;
        $itemFilters[] = $listingType;

        $model = new FindingApiModel($findItemsAdvanced, $itemFilters);

        return $model;
    }
}