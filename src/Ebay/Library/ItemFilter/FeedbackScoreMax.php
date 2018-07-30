<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class FeedbackScoreMax extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->dynamicValue, 1)) {
            return false;
        }

        if (count($this->dynamicValue) !== 1) {
            $this->exceptionMessages[] = $this->name.' can only have one value in the argument array';

            return false;
        }

        if (is_bool($this->dynamicValue[0])) {
            $this->exceptionMessages[] = $this->name.' accepts only actual numbers as arguments, not boolean';

            return false;
        }

        if (!is_int($this->dynamicValue[0]) or $this->dynamicValue[0] < 0) {
            $this->exceptionMessages[] = $this->name.' accepts only numbers (not numeric strings) greater than or equal to zero';

            return false;
        }

        return true;
    }
}