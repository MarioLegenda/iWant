<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class CategoryId extends BaseDynamic
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
                '%s can have only one value and it has to be a valid category id',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        if (!is_int($dynamicValue[0])) {
            $message = sprintf(
                '%s has to be an integer',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}