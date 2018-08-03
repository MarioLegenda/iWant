<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MaxHandlingTime extends BaseDynamic
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
                MaxHandlingTime::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if ($filter <= 1 or !is_int($filter)) {
            $message = sprintf(
                '\'%s\' has to be an integer greater that or equal to 1',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}