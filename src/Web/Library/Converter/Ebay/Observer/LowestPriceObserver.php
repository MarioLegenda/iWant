<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Ebay\Library\Information\SortOrderInformation;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Converter\ItemFilterObservable;
use App\Web\Library\Converter\ItemFilterObserver;
use App\Web\Model\Request\RequestItemFilter;

class LowestPriceObserver implements ItemFilterObserver
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
        $itemFilters = TypedArray::create('string', ItemFilter::class);

        if (array_key_exists('LowestPrice', $webItemFilters)) {
            $freeShippingOnly = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::FREE_SHIPPING_ONLY,
                [true]
            ));

            $sortOrder = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SORT_ORDER,
                [SortOrderInformation::PRICE_PLUS_SHIPPING_LOWEST]
            ));

            $itemFilters['SortOrder'] = $sortOrder;
            $itemFilters['LowestPrice'] = $freeShippingOnly;
        }

        return $itemFilters->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION);
    }
}