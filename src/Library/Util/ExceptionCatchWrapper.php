<?php

namespace App\Library\Util;

class ExceptionCatchWrapper
{
    /**
     * @param \Closure $closure
     */
    public static function run(\Closure $closure): void
    {
        try {
            $closure->__invoke();
        } catch (\Throwable $e) {

        }
    }
}