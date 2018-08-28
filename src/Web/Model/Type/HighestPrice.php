<?php

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class HighestPrice extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'HighestPrice',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'HighestPrice'): TypeInterface
    {
        return parent::fromValue($value);
    }
}