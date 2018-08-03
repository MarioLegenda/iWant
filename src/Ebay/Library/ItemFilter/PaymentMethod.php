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
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        if (!$this->genericValidation($this->getDynamicMetadata()->getDynamicValue(), 1)) {
            $message = sprintf(
                '%s can have only one value and it has to be a valid payment method. Allowed payment methods are %s',
                PaymentMethod::class,
                implode(', ', PaymentMethodInformation::instance()->getAll())
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!PaymentMethodInformation::instance()->has($filter)) {
            $message = sprintf(
                '%s can have only one value and it has to be a valid payment method. Allowed payment methods are %s',
                PaymentMethod::class,
                implode(', ', PaymentMethodInformation::instance()->getAll())
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}