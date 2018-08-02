<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class EndTimeFrom extends BaseDynamic
{
    /**
     * @inheritdoc
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 1)) {
            return false;
        }

        $filter = $dynamicValue[0];

        if (!$filter instanceof \DateTime) {
            $message = sprintf(
                'Invalid value supplied for \'%s\' Value has to be a DateTime instance in the future',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        $currentDateTime = new \DateTime();

        if ($filter->getTimestamp() <= $currentDateTime->getTimestamp()) {
            $message = sprintf(
                'You have to specify a date in the future for \'%s\' item filter',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}