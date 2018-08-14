<?php

namespace App\Tests\Etsy\DataProvider;

use App\Etsy\Library\ItemFilter\ItemFilterType;
use App\Etsy\Library\MethodProcessor\Type\MethodType;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Etsy\Presentation\Model\ItemFilterMetadata;
use App\Etsy\Presentation\Model\ItemFilterModel;
use App\Library\Infrastructure\Helper\TypedArray;

class DataProvider
{
    /**
     * @return EtsyApiModel
     */
    public function getEtsyApiModel(): EtsyApiModel
    {
        $methodType = MethodType::fromKey('findAllListingActive');

        $model = new EtsyApiModel(
            $methodType,
            $this->createItemFilters()
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

        $model = new EtsyApiModel(
            $methodType,
            $this->createItemFilters(
                $limit
            )
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