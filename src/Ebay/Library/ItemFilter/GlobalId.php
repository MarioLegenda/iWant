<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\GlobalIdInformation;

class GlobalId extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic(): bool
    {
        if (!$this->genericValidation($this->getDynamicMetadata()->getDynamicValue(), 1)) {
            $message = sprintf(
                '%s can have only one value',
                GlobalId::class
            );

            throw new \RuntimeException($message);
        }

        $globalIds = array_keys(GlobalIdInformation::instance()->getAll());
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue()[0];

        if (in_array(strtolower($dynamicValue), $globalIds) === false) {
            $message = sprintf(
                'Invalid GLOBAL-ID value. Valid values are %s. %s given',
                implode(', ', array_keys(GlobalIdInformation::instance()->getAll())),
                $dynamicValue
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}