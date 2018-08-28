<?php

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class ShipsToCountry extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'ShipsToCountry',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'ShipsToCountry'): TypeInterface
    {
        return parent::fromValue($value);
    }
}