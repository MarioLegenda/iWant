<?php

namespace App\Library\Middleware;

interface MiddlewareResultInterface
{
    /**
     * @return bool
     */
    public function isFulfilled(): bool;
    /**
     * @return array
     */
    public function getResult(): array;
}