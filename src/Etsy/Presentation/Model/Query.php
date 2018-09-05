<?php

namespace App\Etsy\Presentation\Model;

class Query
{
    /**
     * @var string $value
     */
    private $value;
    /**
     * Query constructor.
     * @param string $value
     */
    public function __construct(
        string $value
    ) {
        $this->value = $value;
    }
    /**
     * @param \Closure|null $closure
     * @return mixed|string
     */
    public function getQuery(\Closure $closure = null)
    {
        if ($closure instanceof \Closure) {
            return $closure->__invoke($this->value);
        }

        return $this->value;
    }
}