<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MaxBids extends BaseDynamic
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
                '%s can have only one value and it has to be an integer greater or equal to 0',
                MaxBids::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_int($filter) or $filter < 0) {
            $message = sprintf(
                '\'%s\' has to be an integer greater that or equal to 0',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}