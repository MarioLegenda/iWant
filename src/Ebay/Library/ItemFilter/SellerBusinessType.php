<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\GlobalIdInformation;

class SellerBusinessType extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->dynamicValue, 2)) {
            return false;
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

        $filter = $this->dynamicValue[0];
        $siteId = $this->dynamicValue[1];

        if (!GlobalIdInformation::instance()->has($siteId)) {
            $this->exceptionMessages[] = $this->name.' item filter can be used only on '.implode(', ', $validSites).' ebay sites. '.$siteId.' given';

            return false;
        }

        foreach ($validSites as $validSiteId) {
            if (!GlobalIdInformation::instance()->has($validSiteId)) {
                $this->exceptionMessages[] = $this->name.' item filter can be used only on '.implode(', ', $validSites).' ebay sites. '.$validSiteId.' given';

                return false;
            }
        }

        $validFilters = array('Business', 'Private');

        if (in_array($filter, $validFilters) === false) {
            $this->exceptionMessages[] = $this->name.' item filter can only accept '.implode(', ', $validFilters);

            return false;
        }

        $this->dynamicValue = array($this->dynamicValue[0]);

        return true;

    }
}