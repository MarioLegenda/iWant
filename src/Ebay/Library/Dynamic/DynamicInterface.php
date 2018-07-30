<?php

namespace App\Ebay\Library\Dynamic;

interface DynamicInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool;
}