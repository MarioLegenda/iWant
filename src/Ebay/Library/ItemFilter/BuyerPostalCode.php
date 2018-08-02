<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class BuyerPostalCode extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!is_string($dynamicValue)) {
            $message = sprintf(
                '%s has to be a string',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}