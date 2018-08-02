<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Helper;

class MaxPrice extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 2)) {
            return false;
        }

        $toValidate = $dynamicValue[0];

        if (!is_float($toValidate)) {
            $message = sprintf(
                '%s has to be an decimal greater than or equal to 0.0',
                $dynamicName
            );

            $this->errors->add($message);

            return false;
        }

        if (Helper::compareFloatNumbers($toValidate, 0.0, '<')) {
            $message = sprintf(
                '%s has to be an decimal greater than or equal to 0.0',
                $dynamicName
            );

            $this->errors->add($message);

            return false;
        }

        return true;
    }
}