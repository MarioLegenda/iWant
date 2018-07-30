<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class Seller extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->dynamicValue)) {
            return false;
        }

        if (count($this->dynamicValue) > 100) {
            $this->exceptionMessages[] = $this->name.' has to be a valid seller name. Up to a 100 sellers can be specified';

            return false;
        }

        return true;
    }
}