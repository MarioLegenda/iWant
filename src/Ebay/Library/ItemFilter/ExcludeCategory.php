<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class ExcludeCategory extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (count($dynamicValue) > 25) {
            $message = sprintf(
                '\'%s\' item filter can accept up to 25 category ids',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        foreach ($dynamicValue as $value) {
            if (!is_numeric($value)) {
                $message = sprintf(
                    'Value \'%s\' has to be a valid category number or a numeric string',
                    $value
                );

                throw new \RuntimeException($message);
            }
        }

        return true;
    }
}