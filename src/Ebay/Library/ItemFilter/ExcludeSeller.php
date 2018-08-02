<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class ExcludeSeller extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (count($dynamicValue) > 100) {
            $message = sprintf(
                '\'%s\' item filter can accept up to 100 seller names',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        foreach ($dynamicValue as $value) {
            if (!is_string($value)) {
                $message = sprintf(
                    '\'%s\' accepts an array of seller names as a string',
                    $dynamicName
                );

                throw new \RuntimeException($message);
            }
        }

        return true;
    }
}