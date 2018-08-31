<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class ExpeditedShippingType extends BaseDynamic implements ItemFilterInterface
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

        $validValues = array('Expedited', 'OneDayShipping');

        if (count($dynamicValue) > 1) {
            $message = sprintf(
                '\'%s\' can have an array with only one argument: %s',
                $dynamicName,
                implode(', ', $validValues)
            );

            throw new \RuntimeException($message);
        }

        $value = $dynamicValue[0];

        if (in_array($value, $validValues) === false) {
            $message = sprintf(
                '\'%s\' can only accept values %s',
                $dynamicName,
                implode(', ', $validValues)
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}