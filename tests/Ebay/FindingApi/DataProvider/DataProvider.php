<?php

namespace App\Tests\Ebay\FindingApi\DataProvider;

use App\Ebay\Library\ItemFilter\OutputSelector;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsAdvanced;
use App\Ebay\Presentation\FindingApi\Model\FindItemsByKeywords;
use App\Ebay\Presentation\FindingApi\Model\FindItemsInEbayStores;
use App\Ebay\Presentation\FindingApi\Model\GetVersion;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class DataProvider
{
    /**
     * @return FindingApiRequestModelInterface
     */
    public function getGetVersionModel(): FindingApiRequestModelInterface
    {
        $getVersion = new GetVersion();

        return new FindingApiModel($getVersion, []);
    }
    /**
     * @param string $keywords
     * @return FindingApiRequestModelInterface
     */
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
            [false]
        ));

        $outputSelector = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::OUTPUT_SELECTOR,
            [[
                'StoreInfo',
                'SellerInfo',
                'GalleryInfo',
                'PictureURLLarge',
                'PictureURLSuperSize',
            ]]
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
        $itemFilters[] = $outputSelector;

        $model = new FindingApiModel($findItemsByKeywords, $itemFilters);

        return $model;
    }
    /**
     * @return FindingApiModel
     */
    public function getInvalidRequestAsModel()
    {
        $query = new Query(
            'keywords',
            'harry potter'
        );

        $invalidQuery = new Query(
            'invalid',
            'value'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $query;
        $queries[] = $invalidQuery;

        $findItemsByKeywords = new FindItemsByKeywords($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

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
    /**
     * @param $keyword
     * @return FindingApiModel
     */
    public function getFindItemsInEbayStores($keyword)
    {
        $keywordsQuery = new Query(
            'keywords',
            $keyword
        );

        $storeName = new Query(
            'storeName',
            'musicMagpie Shop'
        );

        $globalId = new Query(
            'GLOBAL-ID',
            'EBAY-GB'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $globalId;
        $queries[] = $keywordsQuery;
        $queries[] = $storeName;

        $findItemsInEbayStores = new FindItemsInEbayStores($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        $model = new FindingApiModel($findItemsInEbayStores, $itemFilters);

        return $model;
    }
}