<?php

namespace App\Library\Middleware;

interface MiddlewareEntryInterface
{
    /**
     * @param MiddlewareResultInterface|null $middlewareResult
     * @param array|null $parameters
     * @return MiddlewareResultInterface
     */
    public function handle(
        MiddlewareResultInterface $middlewareResult = null,
        array $parameters = null
    ): MiddlewareResultInterface;
}