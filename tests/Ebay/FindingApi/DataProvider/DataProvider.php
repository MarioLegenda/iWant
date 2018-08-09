<?php

namespace App\Tests\Ebay\FindingApi\DataProvider;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsByKeywords;
use App\Ebay\Presentation\FindingApi\Model\ItemFilter;
use App\Ebay\Presentation\FindingApi\Model\ItemFilterMetadata;
use App\Library\Infrastructure\Helper\TypedArray;

class DataProvider
{
    public function getFindItemsByKeywordsData(array $keywords): FindingApiRequestModelInterface
    {
        $keywords = TypedArray::create('integer', 'string', $keywords);

        $findItemsByKeywords = new FindItemsByKeywords(
            'findItemsByKeywords',
            'keywords',
            $keywords
        );

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
}