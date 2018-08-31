<?php

namespace App\Web\Library\Converter\Ebay\Observer;

use App\Ebay\Presentation\FindingApi\Model\ItemFilter;
use App\Ebay\Presentation\FindingApi\Model\ItemFilterMetadata;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Converter\ItemFilterObserver;
use App\Web\Library\Converter\ItemFilterObservable;
use App\Web\Model\Request\RequestItemFilter;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class EntriesPerPageObserver implements ItemFilterObserver
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

        if (array_key_exists('EntriesPerPage', $webItemFilters)) {
            $entriesPerPage = $webItemFilters['EntriesPerPage'];

            $data = $entriesPerPage->getData();

            $entriesPerPageItemFilter = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::ENTRIES_PER_PAGE,
                [$data[0]]
            ));

            $itemFilters['EntriesPerPage'] = $entriesPerPageItemFilter;
        }

        return $itemFilters->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION);
    }
}