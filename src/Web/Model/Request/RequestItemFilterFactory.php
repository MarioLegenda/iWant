<?php

namespace App\Web\Model\Request;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Web\Model\Type\TypeMap;

class RequestItemFilterFactory
{
    /**
     * @param iterable $itemFilters
     * @return iterable
     */
    public static function create(iterable $itemFilters): iterable
    {
        $createdRequestItemFilters = TypedArray::create('string', RequestItemFilter::class);
        foreach ($itemFilters as $itemFilter) {
            $filterType = $itemFilter['filterType'];
            $data = $itemFilter['data'];

            $type = TypeMap::instance()->getTypeMapFor($filterType)::fromValue($filterType);

            $createdRequestItemFilters[$filterType] = new RequestItemFilter($type, $data);
        }

        return $createdRequestItemFilters;
    }
}