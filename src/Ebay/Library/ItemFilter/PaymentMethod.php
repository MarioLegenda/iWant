<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\PaymentMethodInformation;

class PaymentMethod extends BaseDynamic
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

        if (!PaymentMethodInformation::instance()->has($filter)) {
            $this->exceptionMessages[] = $this->name.' has no payment method '.$filter.'. Allowed payment methods are '.implode(', ', PaymentMethodInformation::instance()->getAll());

            return false;
        }

        return true;
    }
}