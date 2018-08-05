<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\SortOrderInformation;

class SortOrder extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s has to be an array with one value',
                SortOrder::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!SortOrderInformation::instance()->has($filter)) {
            $message = sprintf(
                'Invalid value for sortOrder. Please, refer to http://developer.ebay.com/devzone/finding/CallRef/extra/fndItmsByKywrds.Rqst.srtOrdr.html'
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter = null): string
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue()[0];

        return 'sortOrder='.$dynamicValue.'&';
    }
}