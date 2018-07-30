<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\ListingTypeInformation;

class ListingType extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $filter = $this->dynamicValue[0];
        $validFilters = ListingTypeInformation::instance()->getAll();

        if (in_array($filter, $validFilters) === false) {
            $this->exceptionMessages[] = $this->name.' accepts only '.implode(', ', $validFilters).' values';

            return false;
        }

        return true;
    }
}