<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\Result\Result;
use App\Library\Result\ResultInterface;
use App\Tests\App\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class SingleItemEntryPointTest extends BasicSetup
{
    public function test_get_single_item()
    {
        /** @var SingleItemEntryPoint $singleItemEntryPoint */
        $singleItemEntryPoint = $this->locator->get(SingleItemEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.app');

        /** @var SingleItemRequestModel $singleItemRequestModel */
        $singleItemRequestModel = $dataProvider->createSingleItemRequestModel(
            '352381131997',
            'en'
        );

        /** @var ResultInterface $result */
        $result = $singleItemEntryPoint->getSingleItem($singleItemRequestModel);

        static::assertInstanceOf(Result::class, $result);
        static::assertNotEmpty($result->getResult());
    }

    public function test_get_shipping_costs_for_item()
    {
        /** @var SingleItemEntryPoint $singleItemEntryPoint */
        $singleItemEntryPoint = $this->locator->get(SingleItemEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.app');

        /** @var ItemShippingCostsRequestModel $singleItemRequestModel */
        $itemShippingCostsRequestModel = $dataProvider->createItemShippingCostsRequestModel(
            '352381131997',
            'en',
            'IE'
        );

        $result = $singleItemEntryPoint->getShippingCostsForItem($itemShippingCostsRequestModel);

        static::assertInstanceOf(Result::class, $result);
        static::assertNotEmpty($result->getResult());
    }
}