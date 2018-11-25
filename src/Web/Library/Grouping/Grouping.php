<?php

namespace App\Web\Library\Grouping;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Grouping\GroupContract\PriceGroupingInterface;
use App\Web\Library\Grouping\Type\HighestPriceGroupingType;
use App\Web\Library\Grouping\Type\LowestPriceGroupingType;
use App\Web\Model\Response\UniformedResponseModel;


class Grouping
{
    /**
     * @return Grouping
     */
    public static function inst()
    {
        return new static();
    }
    /**
     * Grouping constructor.
     *
     * DO NOT ALLOW IT TO BE INSTANTIATED!
     */
    private function __construct(){}
    /**
     * @param TypeInterface $groupType
     * @param iterable|TypedArray $groupingData
     * @return TypedArray
     */
    public function groupBy(
        TypeInterface $groupType,
        iterable $groupingData
    ): TypedArray {
        if ($groupType->equals(LowestPriceGroupingType::fromValue())) {
            return $this->groupByPriceLowest($groupingData);
        } else if ($groupType->equals(HighestPriceGroupingType::fromValue())) {
            return $this->groupByPriceHighest($groupingData);
        }
    }
    /**
     * @param iterable|TypedArray $data
     * @return TypedArray
     */
    public function groupByPriceLowest(iterable $data): TypedArray
    {
        $dataArray = $data->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION);
        usort($dataArray, function(PriceGroupingInterface $a, PriceGroupingInterface $b) {
            return $a->getPriceForGrouping() >= $b->getPriceForGrouping();
        });

        return TypedArray::create('integer', PriceGroupingInterface::class, $dataArray);
    }
    /**
     * @param iterable|TypedArray $data
     * @return TypedArray
     */
    public function groupByPriceHighest(iterable $data)
    {
        $dataArray = $data->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION);
        usort($dataArray, function(PriceGroupingInterface $a, PriceGroupingInterface $b) {
            return $a->getPriceForGrouping() <= $b->getPriceForGrouping();
        });

        return TypedArray::create('integer', PriceGroupingInterface::class, $dataArray);
    }
}