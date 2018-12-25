<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Library\Infrastructure\Type\BaseType;

class InsuranceOptionCodeType extends BaseType
{
    /**
     * @var array $types
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