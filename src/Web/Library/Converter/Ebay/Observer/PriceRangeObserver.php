<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\ItemFilter\MaxPrice;
use App\Ebay\Library\ItemFilter\MinPrice;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Web\Library\Converter\Ebay\ItemFilterObservable;
use App\Web\Library\Converter\Ebay\ItemFilterObserver;
use App\Web\Model\Request\RequestItemFilter;

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
        if (array_key_exists('PriceRange', $webItemFilters)) {
            $itemFilters = [];
            /** @var RequestItemFilter $priceRangeWebItemFilter */
            $priceRangeWebItemFilter = $webItemFilters['PriceRange'];

            $data = $priceRangeWebItemFilter->getData();

            if (!is_null($data['minPrice'])) {
                $itemFilters[] = new MinPrice(
                    new DynamicMetadata(
                        ItemFilter::MIN_PRICE,
                        [$data['minPrice']]
                    ),
                    new DynamicConfiguration(),
                    new DynamicErrors()
                );
            }

            if (!is_null($data['maxPrice'])) {
                $itemFilters[] = new MaxPrice(
                    new DynamicMetadata(
                        ItemFilter::MAX_PRICE,
                        [$data['maxPrice']]
                    ),
                    new DynamicConfiguration(),
                    new DynamicErrors()
                );
            }

            return $itemFilters;
        }
    }
}