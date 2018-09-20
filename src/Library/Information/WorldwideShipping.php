<?php

namespace App\Library\Information;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class WorldwideShipping extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'Worldwide' => 'Worldwide',
    ];
    /**
     * @param string $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'Worldwide'): TypeInterface
    {
        if (static::$types[array_keys(static::$types)[0]] !== $value) {
            $message = sprintf(
                'Invalid value %s given for Worldwide type',
                $value
            );

            throw new \RuntimeException($message);
        }

        return parent::fromValue($value);
    }
}