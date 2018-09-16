<?php

namespace App\Tests\Etsy\DataProvider;

use App\Etsy\Library\ItemFilter\ItemFilterType;
use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterMetadata;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Etsy\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;

class DataProvider
{
    /**
     * @return EtsyApiModel
     */
    public function getEtsyApiModel(): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingActive');

        $queries = TypedArray::create('integer', Query::class);

        $listingsActiveQuery = new Query('/listings/active?');

        $queries[] = $listingsActiveQuery;

        $model = new EtsyApiModel(
            $methodType,
            $this->createItemFilters(),
            $queries
        );

        return $model;
    }
    /**
     * @return EtsyApiModel
     */
    public function createFindAllShopListingsFeatured(): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllShopListingsFeatured');

        $queries = TypedArray::create('integer', Query::class);

        $shopsPart = new Query('/shops/');
        $shopId = new Query('AnnKirillartPlace/listings/featured');

        $queries[] = $shopsPart;
        $queries[] = $shopId;

        $model = new EtsyApiModel(
            $methodType,
            $this->createItemFilters(),
            $queries
        );

        return $model;
    }

    /**
     * @param int $limit
     * @return EtsyApiModel
     */
    public function getEtsyApiModelWithLimit(int $limit): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingActive');

        $queries = TypedArray::create('integer', Query::class);

        $listingsActiveQuery = new Query('/listings/active?');

        $queries[] = $listingsActiveQuery;

        $model = new EtsyApiModel(
            $methodType,
            $this->createItemFilters(
                $limit
            ),
            $queries
        );

        return $model;
    }
    /**
     * @param string $listingId
     * @return EtsyApiModel
     */
    public function getEtsyGetListingModel(string $listingId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('getListing');

        $queries = TypedArray::create('integer', Query::class);

        $listingIdQuery = new Query(sprintf('/listings/%s?', $listingId));

        $queries[] = $listingIdQuery;

        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $model = new EtsyApiModel(
            $methodType,
            $itemFilters,
            $queries
        );

        return $model;
    }
    /**
     * @param int $limit
     * @return TypedArray
     */
    private function createItemFilters(
        $limit = 5
    ): TypedArray
    {
        $itemFilters = TypedArray::create('integer', ItemFilterModel::class);

        $keywordsModelMetadata = new ItemFilterMetadata(
            ItemFilterType::fromKey('Keywords'),
            ['boots, mountain']
        );

        $limitMetadata = new ItemFilterMetadata(
            ItemFilterType::fromKey('Limit'),
            [$limit]
        );

        $minPriceMetadata = new ItemFilterMetadata(
            ItemFilterType::fromKey('MinPrice'),
            [25.0]
        );

        $itemFilters[] = new ItemFilterModel($keywordsModelMetadata);
        $itemFilters[] = new ItemFilterModel($limitMetadata);
        $itemFilters[] = new ItemFilterModel($minPriceMetadata);

        return $itemFilters;
    }
}