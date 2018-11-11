<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ReturnPolicy\Type;

use App\Library\Infrastructure\Type\BaseType;

class RefundType extends BaseType
{
    protected static $types = [
        'MoneyBack',
        'MoneyBackOrExchange',
        'MoneyBackOrReplacement',
    ];
}