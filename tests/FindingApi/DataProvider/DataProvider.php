<?php

namespace App\Tests\FindingApi\DataProvider;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\Model\FindingApiModel;
use App\Ebay\Presentation\Model\FindItemsByKeywords;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Library\Infrastructure\Helper\TypedArray;

class DataProvider
{
    public function getFindItemsByKeywordsData(): FindingApiRequestModelInterface
    {
        $keywords = TypedArray::create('integer', 'string', [
            'boots',
            'mountain',
        ]);

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
            [25]
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
     * @return TypedArray
     */
    private function getDiverseItemFilters(): TypedArray
    {
        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        for ($i = 0; $i < 5; $i++) {
            $metadata = new ItemFilterMetadata(
                'name',
                'value',
                'MaxPrice',
                [25]
            );
        }
    }
}