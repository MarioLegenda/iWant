<?php

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class Used extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'Used',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'Used'): TypeInterface
    {
        return parent::fromValue($value);
    }
}