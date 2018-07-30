<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class BuyerPostalCode extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        return true;
    }
}