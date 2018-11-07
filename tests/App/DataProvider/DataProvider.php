<?php

namespace App\Tests\App\DataProvider;

use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;

class DataProvider
{
    /**
     * @param string $itemId
     * @return SingleItemRequestModel
     */
    public function createSingleItemRequestModel(
        string $itemId
    ): SingleItemRequestModel {
        return new SingleItemRequestModel($itemId);
    }
    /**
     * @return SingleItemOptionsModel
     */
    public function createFakeSingleItemOptionsModel(string $itemId = null): SingleItemOptionsModel
    {
        $itemId = (is_string($itemId)) ? $itemId : rand(99999, 9999999);

        return new SingleItemOptionsModel($itemId);
    }
}