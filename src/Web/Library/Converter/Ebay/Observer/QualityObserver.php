<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\ItemFilter\Condition;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Web\Library\Converter\Ebay\ItemFilterObservable;
use App\Web\Library\Converter\Ebay\ItemFilterObserver;
use App\Web\Model\Request\RequestItemFilter;

class QualityObserver implements ItemFilterObserver
{
    /**
     * @param ItemFilterObservable $observable
     * @param array|RequestItemFilter[] $webItemFilters
     * @return array
     */
    public function update(
        ItemFilterObservable $observable,
        array $webItemFilters
    ): array {
        $itemFilters = [];

        $qualityValues = [
            'HighQuality' => [1000, 1500, 1750],
            'Used' => [3000, 5000, 6000],
        ];

        $conditionValues = [];

        foreach ($qualityValues as $filterTypeName => $qualityValue) {
            if (array_key_exists($filterTypeName, $webItemFilters)) {
                $conditionValues = array_merge($qualityValues[$filterTypeName], $conditionValues);
            }
        }

        $conditionItemFilter = new Condition(
            new DynamicMetadata(
                ItemFilter::CONDITION,
                $conditionValues
            ),
            new DynamicConfiguration(false, false),
            new DynamicErrors()
        );

        $itemFilters[] = $conditionItemFilter;

        return $itemFilters;
    }
}