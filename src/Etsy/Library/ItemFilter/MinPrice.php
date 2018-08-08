<?php

namespace App\Etsy\Library\ItemFilter;

use App\Etsy\Library\Dynamic\BaseDynamic;

class MinPrice extends BaseDynamic
{
    /**
     * @inheritdoc
     */
    public function validateDynamic(): bool
    {
        $dynamicValue = $this->dynamicMetadata->getDynamicValue();
        $dynamicName = $this->dynamicMetadata->getName();

        if (count($dynamicValue) !== 1) {
            $message = sprintf(
                '%s has to be an array argument with only one value',
                $dynamicName
            );

            $this->errors->add($message);

            throw new \RuntimeException($message);
        }

        if (!is_float($dynamicValue[0])) {
            $message = sprintf(
                '%s has to be a float greater than 0.0',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        if ($dynamicValue[0] < 0) {
            $message = sprintf(
                '%s has to be an float greater than 0',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}