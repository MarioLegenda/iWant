<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\Shipping\Type;

use App\Library\Infrastructure\Type\BaseType;

class InsuranceOption extends BaseType
{
    /**
     * @var array
     */
    protected static $types = [
        'CustomCode',
        'IncludedInShippingHandling',
        'NotOffered',
        'NotOfferedOnSite',
        'Optional',
        'Required',
    ];
}