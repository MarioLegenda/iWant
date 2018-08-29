<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Ebay\Presentation\FindingApi\Model\ItemFilter;
use App\Ebay\Presentation\FindingApi\Model\ItemFilterMetadata;
use App\Web\Library\Converter\Ebay\ItemFilterObservable;
use App\Web\Library\Converter\Ebay\ItemFilterObserver;
use App\Web\Model\Request\RequestItemFilter;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

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

        $condition = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::CONDITION,
            $conditionValues
        ));

        $itemFilters[] = $condition;

        return $itemFilters;
    }
}