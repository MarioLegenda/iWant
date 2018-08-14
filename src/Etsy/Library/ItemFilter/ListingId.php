<?php

namespace App\Etsy\Library\ItemFilter;

use App\Etsy\Library\Dynamic\BaseDynamic;

class ListingId extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic(): bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (count($dynamicValue) !== 1) {
            $message = sprintf(
                '%s has to be an array argument with only one value',
                $dynamicName
            );

            $this->errors->add($message);

            throw new \RuntimeException($message);
        }

        $value = $dynamicValue[0];

        if (!is_int($value)) {
            $message = sprintf(
                '%s has to be an integer in %s',
                $dynamicName,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }
    }
}