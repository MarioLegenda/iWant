<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\CurrencyInformation;

class Currency extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->getDynamicMetadata()->getDynamicValue(), 1)) {
            return false;
        }

        $allowedCurrencies = CurrencyInformation::instance()->getAll();

        $currency = strtoupper($this->getDynamicMetadata()->getDynamicValue()[0]);

        if (in_array($currency, $allowedCurrencies) === false) {
            $message = sprintf(
                'Invalid Currency item filter value supplied. Allowed currencies are %s',
                implode(',', $allowedCurrencies)
            );

            $this->errors->add($message);

            throw new \RuntimeException($message);
        }

        return true;
    }
}