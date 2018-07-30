<?php

namespace App\Library\Http\Request\Type;

use App\Library\Infrastructure\Type\TypeInterface;

class HttpTypeFactory
{
    /**
     * @param string $type
     * @return TypeInterface
     */
    public static function create(?string $type): TypeInterface
    {
        $types = [
            'get' => GetHttpType::class,
            'post' => PostHttpType::class,
            'patch' => PatchHttpType::class,
            'put' => PutHttpType::class,
        ];

        $type = strtolower($type);

        if (!array_key_exists($type, $types)) {
            $message = sprintf(
                'Cannot create http type. Invalid type \'%s\' given',
                $type
            );

            throw new \RuntimeException($message);
        }

        return $types[$type]::{'fromValue'}($type);
    }
}