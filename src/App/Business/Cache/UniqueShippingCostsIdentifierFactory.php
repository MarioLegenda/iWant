<?php

namespace App\App\Business\Cache;

use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;

class UniqueShippingCostsIdentifierFactory
{
    /**
     * @param ItemShippingCostsRequestModel $model
     * @return string
     */
    public static function createIdentifier(ItemShippingCostsRequestModel $model): string
    {
        return md5(serialize([
            'itemId' => $model->getItemId(),
            'destinationCountryCode' => $model->getDestinationCountryCode(),
        ]));
    }
}