<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicInterface;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\Information\SortOrderInformation;
use App\Ebay\Library\ItemFilter\FreeShippingOnly;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\ItemFilter\SortOrder;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Converter\Ebay\ItemFilterObservable;
use App\Web\Library\Converter\Ebay\ItemFilterObserver;
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
        $itemFilters = TypedArray::create('string', DynamicInterface::class);

        if (array_key_exists('LowestPrice', $webItemFilters)) {
            $freeShippingOnly = new FreeShippingOnly(
                new DynamicMetadata(ItemFilter::FREE_SHIPPING_ONLY, [true]),
                new DynamicConfiguration(false, false),
                new DynamicErrors()
            );

            $sortOrder = new SortOrder(
                new DynamicMetadata(ItemFilter::SORT_ORDER, [SortOrderInformation::PRICE_PLUS_SHIPPING_LOWEST]),
                new DynamicConfiguration(false, false),
                new DynamicErrors()
            );

            $itemFilters['SortOrder'] = $sortOrder;
            $itemFilters['LowestPrice'] = $freeShippingOnly;
        }

        return $itemFilters->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION);
    }
}