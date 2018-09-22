<?php

namespace App\Component\TodayProducts\Selector\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class Nan extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'NaN'
    ];
    /**
     * @param string $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'NaN'): TypeInterface
    {
        return parent::fromValue($value);
    }
}