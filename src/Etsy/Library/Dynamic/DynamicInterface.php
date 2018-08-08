<?php

namespace App\Etsy\Library\Dynamic;

interface DynamicInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool;
}