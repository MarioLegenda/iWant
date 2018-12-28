<?php

namespace App\App\Business\Middleware;

use App\Library\Middleware\MiddlewareResultInterface;

class MiddlewareResult implements MiddlewareResultInterface
{
    /**
     * @var bool $isFulfilled
     */
    private $isFulfilled = false;
    /**
     * @var array $result
     */
    private $result;
    /**
     * MiddlewareResult constructor.
     * @param array $result
     * @param bool $isFulfilled
     */
    public function __construct(
        ?array $result,
        bool $isFulfilled
    ) {
        $this->isFulfilled = $isFulfilled;
        $this->result = $result;
    }
    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
    /**
     * @return bool
     */
    public function isFulfilled(): bool
    {
        return $this->isFulfilled;
    }
}