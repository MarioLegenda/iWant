<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MaxQuantity extends BaseDynamic implements ItemFilterInterface
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
                '%s can have only one value and it has to be an integer greater that or equal to 1',
                MaxQuantity::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_int($filter) or $filter < 1) {
            $message = sprintf(
                '\'%s\' has to be an integer greater than or equal to 1',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}