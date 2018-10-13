<?php

namespace App\Library\Util;

class Environment
{
    /**
     * @var string $env
     */
    private $env;
    /**
     * Environment constructor.
     * @param string $env
     */
    public function __construct(string $env)
    {
        $this->env = $env;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->env;
    }
}