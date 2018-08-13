<?php

namespace App\Library\Information;

use App\Library\Infrastructure\Type\BaseType;

class ShopType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'bonanza' => 'Bonanza',
        'etsy' => 'Etsy',
        'ebay' => 'Ebay',
    ];
}