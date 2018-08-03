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
                '%s can have only one value, true or false',
                MaxBids::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if ($filter < 0 or !is_int($filter)) {
            $message = sprintf(
                '\'%s\' has to be an integer greater that or equal to 1',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}