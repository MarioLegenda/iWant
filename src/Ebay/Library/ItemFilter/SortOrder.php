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
        if (!empty($this->filter)) {
            if (!$this->genericValidation($this->filter, 1)) {
                return false;
            }

            $filter = $this->filter[0];

            if (!SortOrderInformation::instance()->has($filter)) {
                $this->exceptionMessages[] = 'Invalid value for sortOrder. Please, refer to http://developer.ebay.com/devzone/finding/CallRef/extra/fndItmsByKywrds.Rqst.srtOrdr.html';

                return false;
            }
        }

        return true;
    }
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter): string
    {
        return 'sortOrder='.$this->dynamicValue[0].'&';
    }
}