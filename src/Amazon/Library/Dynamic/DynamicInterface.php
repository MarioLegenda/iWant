<?php

namespace App\Amazon\Library\Dynamic;

interface DynamicInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool;
}