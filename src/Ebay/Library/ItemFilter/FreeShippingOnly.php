<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class FreeShippingOnly extends BaseDynamic implements ItemFilterInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->getDynamicMetadata()->getDynamicValue(), 1)) {
            $message = sprintf(
                '%s can have only one value, true or false',
                FreeShippingOnly::class
            );

            throw new \RuntimeException($message);
        }

        if (parent::checkBoolean($this->getDynamicMetadata()->getDynamicValue()[0]) === false) {
            $message = sprintf(
                '%s can have only one value, true or false. Non boolean value given',
                FreeShippingOnly::class
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}