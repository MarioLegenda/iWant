<?php

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class LowestPrice extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'LowestPrice',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'LowestPrice'): TypeInterface
    {
        return parent::fromValue($value);
    }
}