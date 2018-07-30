<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MinBids extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->dynamicValue, 1)) {
            return false;
        }

        $filter = $this->dynamicValue[0];

        if ($filter < 0) {
            $this->exceptionMessages[] = $this->name.' has to be an integer greater than or equal to 0';

            return false;
        }

        return true;
    }
}