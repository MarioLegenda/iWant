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
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        $filter = $dynamicValue[0];
        $validFilters = ListingTypeInformation::instance()->getAll();

        if (in_array($filter, $validFilters) === false) {
            $message = sprintf(
                '%s accepts only \'%s\' values',
                $dynamicName,
                implode(', ', $validFilters)
            );

            $this->errors->add($message);

            throw new \RuntimeException($message);
        }

        return true;
    }
}