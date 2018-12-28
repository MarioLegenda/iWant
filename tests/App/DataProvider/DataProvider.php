<?php

namespace App\Tests\App\DataProvider;

use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;

class DataProvider
{
    /**
     * @param string $itemId
     * @param string $locale
     * @return SingleItemRequestModel
     */
    public function createSingleItemRequestModel(
        string $itemId,
        string $locale = 'en'
    ): SingleItemRequestModel {
        return new SingleItemRequestModel($itemId, $locale);
    }
    /**
     * @param string|null $itemId
     * @param string $locale
     * @return SingleItemOptionsModel
     */
    public function createFakeSingleItemOptionsModel(
        string $itemId = null,
        string $locale = 'en'
    ): SingleItemOptionsModel {
        $itemId = (is_string($itemId)) ? $itemId : rand(99999, 9999999);

        return new SingleItemOptionsModel($itemId, $locale);
    }
    /**
     * @param string $itemId
     * @param string $locale
     * @param string $destinationCountryCode
     * @return ItemShippingCostsRequestModel
     */
    public function createItemShippingCostsRequestModel(
        string $itemId,
        string $locale,
        string $destinationCountryCode
    ): ItemShippingCostsRequestModel {
        return new ItemShippingCostsRequestModel(
            $itemId,
            $locale,
            $destinationCountryCode
        );
    }
}