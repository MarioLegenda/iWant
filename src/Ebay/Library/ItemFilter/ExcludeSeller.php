<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class ExcludeSeller extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (count($this->dynamicValue) > 100) {
            $this->exceptionMessages[] = 'ExcludeSeller item filter can accept up to 100 seller names';

            return false;
        }

        foreach ($this->dynamicValue as $value) {
            if (!is_string($value)) {
                $this->exceptionMessages[] = 'ExcludeSeller accepts an array of seller names as a string';

                return false;
            }
        }

        return true;
    }
}