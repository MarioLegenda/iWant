<?php

namespace App\Etsy\Library\ItemFilter;

use App\Etsy\Library\Information\SortOnInformation;
use App\Etsy\Library\Dynamic\BaseDynamic;

class SortOn extends BaseDynamic
{
    /**
     * @inheritdoc
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

        if (!SortOnInformation::instance()->has($dynamicValue[0])) {
            $message = sprintf(
                '%s does not support value \'%s\'',
                $dynamicName,
                $dynamicValue[0]
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}