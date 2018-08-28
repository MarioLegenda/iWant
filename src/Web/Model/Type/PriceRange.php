<?php

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class PriceRange extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'PriceRange',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'PriceRange'): TypeInterface
    {
        return parent::fromValue($value);
    }
}