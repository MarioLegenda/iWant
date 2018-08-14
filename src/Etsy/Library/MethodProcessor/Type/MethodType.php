<?php

namespace App\Etsy\Library\MethodProcessor\Type;

use App\Library\Infrastructure\Type\BaseType;

class MethodType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'findAllListingActive' => 'FindAllListingActive',
    ];
}