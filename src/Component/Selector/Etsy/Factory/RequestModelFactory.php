<?php

namespace App\Component\Selector\Etsy\Factory;

use App\Etsy\Library\Type\MethodType;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Yandex\Presentation\Model\Query;

class RequestModelFactory
{
    /**
     * @param string $listingId
     * @return EtsyApiModel
     */
    public function createShippingInfoModel(string $listingId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingShippingProfileEntries');

        $queries = TypedArray::create('integer', Query::class);

        $listingIdQuery = new Query(sprintf('/listings/%s/shipping/info?', $listingId));

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
     * @param string $countryId
     * @return EtsyApiModel
     */
    public function createCountryModel(string $countryId): EtsyApiModel
    {
        $methodType = MethodType::fromKey('getCountry');

        $queries = TypedArray::create('integer', Query::class);

        $listingIdQuery = new Query(sprintf('/countries/%s?', $countryId));

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