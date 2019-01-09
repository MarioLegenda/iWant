<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Information\ISO3166CountryCodeInformation;
use App\Ebay\Library\Dynamic\BaseDynamic;

class AvailableTo extends BaseDynamic implements ItemFilterInterface
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

            throw new \RuntimeException($message);
        }

        $userCodes = $dynamicValue[0];

        if (count($userCodes) > 1) {
            $message = sprintf(
                'Item filter %s accepts only one country code',
                $dynamicName
            );

            $this->errors->add($message);

            throw new \RuntimeException($message);
        }

        if (ISO3166CountryCodeInformation::instance()->has($userCodes[0]) === false) {
            $message = sprintf(
                '%s has to receive an array with one value. Also, AvailableTo has to be a valid ISO 3166 country name. Please, refer to https://www.iso.org/obp/ui/#search\'',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
    /**
     * @param int|null $counter
     * @return string
     */
    public function urlify(int $counter = null): string
    {
        $dynamicName = $this->getDynamicMetadata()->getName();
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue()[0];

        return sprintf(
            'itemFilter(%d).name=%s&itemFilter(%d).value=%s&',
            $counter,
            $dynamicName,
            $counter,
            $dynamicValue[0]
        );
    }
}