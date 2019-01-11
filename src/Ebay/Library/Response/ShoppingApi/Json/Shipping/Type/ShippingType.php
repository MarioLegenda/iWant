<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\Shipping\Type;

use App\Library\Infrastructure\Type\BaseType;

class ShippingType extends BaseType
{
    /**
     * @var array
     */
    protected static $types = [
        'Calculated',
        'CalculatedDomesticFlatInternational',
        'CustomCode',
        'Flat',
        'FlatDomesticCalculatedInternational',
        'Free',
        'Freight',
        'NotSpecified',
    ];
}