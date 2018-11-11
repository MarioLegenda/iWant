<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ReturnPolicy\Type;

use App\Library\Infrastructure\Type\BaseType;

class ReturnType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'ReturnsAccepted',
        'ReturnsNotAccepted',
    ];
}