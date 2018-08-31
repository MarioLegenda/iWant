<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class FeedbackScoreMax extends BaseDynamic implements ItemFilterInterface
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
                '%s has to be an array argument with only one value',
                $dynamicName
            );

            $this->errors->add($message);

            throw new \RuntimeException($message);
        }

        if (count($dynamicValue) !== 1) {
            $message = sprintf(
                '\'%s\' can only have one value in the argument array',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        if (is_bool($dynamicValue[0])) {
            $message = sprintf(
                '\'%s\' accepts only actual numbers as arguments, not boolean',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        if (!is_int($dynamicValue[0]) or $dynamicValue[0] < 0) {
            $message = sprintf(
                '\'%s\' accepts only numbers (not numeric strings) greater than or equal to zero',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}