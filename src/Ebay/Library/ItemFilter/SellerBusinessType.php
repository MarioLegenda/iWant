<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\GlobalIdInformation;

class SellerBusinessType extends BaseDynamic implements ItemFilterInterface
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
                '%s can only be an array with one array as its value',
                SellerBusinessType::class
            );

            throw new \RuntimeException($message);
        }

        $validSites = array(
            'EBAY-AT',
            'EBAY-NLBE',
            'EBAY-NLBE',
            'EBAY-FR',
            'EBAY-DE',
            'EBAY-IE',
            'EBAY-IT',
            'EBAY-PL',
            'EBAY-ES',
            'EBAY-CH',
            'EBAY-GB',
        );

        $validBusinessTypes = [
            'Business',
            'Private'
        ];

        $filter = $dynamicValue[0];

        if (!array_key_exists('siteId', $filter) or !array_key_exists('businessType', $filter)) {
            $message = sprintf(
                '%s accepts an array with keys \'siteId\' that has to be either one of %s and \'businessType\' that can be either one of %s',
                $dynamicName,
                implode(', ', $validSites),
                implode(', ', $validBusinessTypes)
            );

            throw new \RuntimeException($message);
        }

        $siteId = $filter['siteId'];
        $businessType = $filter['businessType'];

        if (!GlobalIdInformation::instance()->has($siteId)) {
            $message = sprintf(
                '%s item filter can be used only on %s ebay sites. %s given',
                $dynamicName,
                implode(', ', $validSites),
                $siteId
            );

            throw new \RuntimeException($message);
        }

        if (in_array($siteId, $validSites) === false) {
            $message = sprintf(
                '%s item filter can be used only on %s ebay sites. %s given',
                $dynamicName,
                implode(', ', $validSites),
                $siteId
            );

            throw new \RuntimeException($message);
        }

        if (in_array($businessType, $validBusinessTypes) === false) {
            $message = sprintf(
                '%s accepts an array with keys \'siteId\' that has to be either one of %s and \'businessType\' that can be either one of %s',
                $dynamicName,
                implode(', ', $validSites),
                implode(', ', $validBusinessTypes)
            );

            throw new \RuntimeException($message);
        }

        return true;

    }
}