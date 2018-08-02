<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Library\Util\Util;

class EndTimeTo extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 1)) {
            return false;
        }

        if (!Util::isValidDate($dynamicValue[0])) {
            $message = sprintf(
                'Invalid format supplied for %s',
                EndTimeFrom::class
            );

            throw new \RuntimeException($message);
        }

        $filter = Util::toDateTime($dynamicValue[0]);

        if (!$filter instanceof \DateTime) {
            $message = sprintf(
                'Invalid value supplied for \'%s\'. Value has to be a DateTime instance in the past or equal to this day',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        $currentDateTime = new \DateTime();

        $filter->setTimezone(new \DateTimeZone('UTC'));
        $currentDateTime->setTimezone(new \DateTimeZone('UTC'));

        $filterDateTime = new \DateTime($filter->format('Y-m-d'));
        $currentDT = new \DateTime($currentDateTime->format('Y-m-d'));

        if ($filterDateTime->getTimestamp() < $currentDT->getTimestamp()) {
            $message = sprintf(
                'You have to specify a date in the future or equal to this day for \'%s\' item filter',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}