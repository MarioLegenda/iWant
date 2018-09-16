<?php

namespace App\Etsy\Library\Type;

use App\Library\Infrastructure\Type\BaseType;

class MethodType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'findAllListingActive' => 'FindAllShopListingsActive',
        'findAllShopListingsFeatured' => 'FindAllShopListingsFeatured',
        'getListing' => 'GetListing',
    ];
}