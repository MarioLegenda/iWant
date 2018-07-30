<?php

namespace App\Library\Infrastructure;

interface ServiceFilterInterface
{
    /**
     * @param \Closure $filter
     * @return mixed
     */
    public function filter(\Closure $filter);
}