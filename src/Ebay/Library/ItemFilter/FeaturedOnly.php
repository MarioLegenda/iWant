<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class FeaturedOnly extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can be only one value, true or false',
                $dynamicName
            );

            $this->errors->add($message);

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