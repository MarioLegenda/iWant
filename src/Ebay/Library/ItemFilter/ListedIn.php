<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

use App\Ebay\Library\Information\GlobalIdInformation;

class ListedIn extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 1)) {
            return false;
        }

        $filter = $dynamicValue[0];

        if (!GlobalIdInformation::instance()->has($filter)) {
            $message = sprintf(
                '\'%s\' has to have a valid global id. Please, refer to http://developer.ebay.com/devzone/finding/callref/Enums/GlobalIdList.html or use FindingAPI\Core\ItemFilter\GlobalId object',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}