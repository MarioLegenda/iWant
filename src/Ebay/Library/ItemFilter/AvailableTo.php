<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Information\ISO3166CountryCodeInformation;
use App\Ebay\Library\Dynamic\BaseDynamic;

class AvailableTo extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->dynamicMetadata->getDynamicValue();
        $dynamicName = $this->dynamicMetadata->getName();

        if (count($dynamicValue) !== 1) {
            $message = sprintf(
                '%s has to be an array argument with only one value',
                $dynamicName
            );

            $this->errors->add($message);

            return false;
        }

        $userCode = $dynamicValue[0];

        if (ISO3166CountryCodeInformation::instance()->has($userCode) === false) {
            $message = sprintf(
                '%s  has to receive an array with one value. Also, AvailableTo has to be a valid ISO 3166 country name. Please, refer to https://www.iso.org/obp/ui/#search\'',
                $dynamicName
            );

            $this->errors->add($message);

            return false;
        }

        return true;
    }
}