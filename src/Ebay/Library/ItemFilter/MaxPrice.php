<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Helper;

class MaxPrice extends BaseDynamic
{
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter): string
    {
        $itemFilterString = parent::urlify($counter);

        if ($this->getDynamicMetadata()->hasParamOption()) {
            $paramName = $this->getDynamicMetadata()->getParamName();
            $paramValue = $this->getDynamicMetadata()->getParamValue();

            $itemFilterString.=sprintf(
                'itemFilter(%d).paramName=%s&itemFilter(%d).paramValue=%s&',
                $counter,
                $paramName,
                $counter,
                $paramValue
            );
        }

        return $itemFilterString;
    }

    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can have only one value, true or false',
                MaxPrice::class
            );

            throw new \RuntimeException($message);
        }

        $toValidate = $dynamicValue[0];

        if (!is_float($toValidate)) {
            $message = sprintf(
                '%s has to be an decimal greater than or equal to 0.0. %f given',
                $dynamicName,
                $toValidate
            );

            throw new \RuntimeException($message);
        }

        if (Helper::compareFloatNumbers($toValidate, 0.0, '<')) {
            $message = sprintf(
                '%s has to be an decimal greater than or equal to 0.0. %f given',
                $dynamicName,
                $toValidate
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}