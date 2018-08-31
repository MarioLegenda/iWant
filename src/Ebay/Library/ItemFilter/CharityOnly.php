<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class CharityOnly extends BaseDynamic implements ItemFilterInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can have only one value, true or false',
                CharityOnly::class
            );

            throw new \RuntimeException($message);
        }

        if (parent::checkBoolean($dynamicValue[0]) === false) {
            $message = sprintf(
                '%s can have only one value, true or false. Non boolean value given',
                CharityOnly::class
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}