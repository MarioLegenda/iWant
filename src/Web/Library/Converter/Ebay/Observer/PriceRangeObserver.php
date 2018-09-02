<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Converter\ItemFilterObservable;
use App\Web\Library\Converter\ItemFilterObserver;
use App\Web\Model\Request\RequestItemFilter;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class PriceRangeObserver implements ItemFilterObserver
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
        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        if (array_key_exists('PriceRange', $webItemFilters)) {
            /** @var RequestItemFilter $priceRangeWebItemFilter */
            $priceRangeWebItemFilter = $webItemFilters['PriceRange'];

            $data = $priceRangeWebItemFilter->getData();

            if (!is_null($data['minPrice'])) {
                $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                    'name',
                    'value',
                    ItemFilterConstants::MIN_PRICE,
                    [(float) $data['minPrice']]
                ));
            }

            if (!is_null($data['maxPrice'])) {
                $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                    'name',
                    'value',
                    ItemFilterConstants::MAX_PRICE,
                    [(float) $data['maxPrice']]
                ));
            }
        }

        return $itemFilters->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION);
    }
}