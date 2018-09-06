<?php

namespace App\Library;

use App\Library\Infrastructure\Type\BaseType;

class MarketplaceType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'Ebay',
        'Amazon',
        'Etsy',
        'Viagogo',
        'ticketmaster',
    ];
}