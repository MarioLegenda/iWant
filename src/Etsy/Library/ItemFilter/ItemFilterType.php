<?php

namespace App\Etsy\Library\ItemFilter;

use App\Library\Infrastructure\Type\BaseType;

class ItemFilterType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'SortOn' => 'sort_on',
        'Keywords' => 'keywords',
        'Limit' => 'limit',
        'MaxPrice' => 'max_price',
        'MinPrice' => 'min_price',
        'Offset' => 'offset',
        'Page' => 'page',
        'SortOrder' => 'sort_order',
    ];
}