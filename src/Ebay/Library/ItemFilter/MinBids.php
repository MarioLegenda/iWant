<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MinBids extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can have only one value and it has to be an integer greater than or equal to 0',
                MinBids::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_int($filter) or $filter < 0) {
            $message = sprintf(
                '\'%s\' has to be an integer greater than or equal to 0',
                $filter
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}