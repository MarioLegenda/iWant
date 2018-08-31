<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\ISO3166CountryCodeInformation;

class LocatedIn extends BaseDynamic implements ItemFilterInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (count($dynamicValue) > 25) {
            $message = sprintf(
                '\'%s\' can specify up to 25 countries. %d given',
                $dynamicName,
                count($dynamicValue)
            );

            throw new \RuntimeException($message);
        }

        foreach ($dynamicValue as $code) {
            if (!ISO3166CountryCodeInformation::instance()->has($code)) {
                $message = sprintf(
                    'Unknown ISO31566 country code %s. Please, refere to https://www.iso.org/obp/ui/#search',
                    $code
                );

                throw new \RuntimeException($message);
            }
        }

        return true;
    }
}