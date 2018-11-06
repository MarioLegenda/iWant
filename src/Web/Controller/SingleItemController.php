<?php

namespace App\Web\Controller;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;

class SingleItemController
{
    public function optionsCheckSingleItem(
        SingleItemOptionsModel $model,
        SingleItemEntryPoint $singleItemEntryPoint
    ) {
        $optionsResponseModel = $singleItemEntryPoint->optionsCheckSingleItem($model);
    }
}