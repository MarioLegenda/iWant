<?php

namespace App\Library\Tools;

class TypeParser
{
    /**
     * @param string $boolean
     * @return bool
     */
    public static function parseBooleanFromString(string $boolean): bool
    {
        return strtolower($boolean) === 'true';
    }
}