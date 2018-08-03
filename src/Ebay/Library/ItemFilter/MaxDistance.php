<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MaxDistance extends BaseDynamic
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
                '%s can have only one value, true or false',
                MaxDistance::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if ($filter < 5) {
            $message = sprintf(
                '\'%s\' has to be a number greater than or equal to 5',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}