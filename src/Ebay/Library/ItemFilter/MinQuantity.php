<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class MinQuantity extends BaseDynamic
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
                '%s can have only one value and it has to be a float greater than or equal to 1',
                MinQuantity::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_int($filter)) {
            $message = sprintf(
                '\'%s\' has to be an integer greater than or equal to 1',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        if ($filter < 1) {
            $message = sprintf(
                '\'%s\' has to be an integer greater than or equal to 1',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}