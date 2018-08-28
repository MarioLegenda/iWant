<?php

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class HighQuality extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'HighQuality',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'HighQuality'): TypeInterface
    {
        return parent::fromValue($value);
    }
}