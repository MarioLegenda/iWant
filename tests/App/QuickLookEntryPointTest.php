<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\QuickLookEntryPoint;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Tests\App\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class QuickLookEntryPointTest extends BasicSetup
{
    public function test_quick_look_entire_process()
    {
        $conn = $this->locator->get('doctrine')->getManager()->getConnection();

        $conn->exec('TRUNCATE TABLE single_product_item');

        /** @var QuickLookEntryPoint $quickLookEntryPoint */
        $quickLookEntryPoint = $this->locator->get(QuickLookEntryPoint::class);

        $itemId = '382440661440';

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.app');

        $optionsModel = $dataProvider->createFakeSingleItemOptionsModel($itemId);

        /** @var SingleItemOptionsResponse $optionsResponse */
        $optionsResponse = $quickLookEntryPoint->optionsCheckSingleItem($optionsModel);

        static::assertInstanceOf(SingleItemOptionsResponse::class, $optionsResponse);
        static::assertEquals($optionsResponse->getMethod(), 'PUT');

        $singleItemRequestModel = $dataProvider->createSingleItemRequestModel($itemId);

        /** @var SingleItemResponseModel $putResponse */
        $putResponse = $quickLookEntryPoint->putSingleItem($singleItemRequestModel);

        static::assertInstanceOf(SingleItemResponseModel::class, $putResponse);
        static::assertInternalType('int', (int) $putResponse->getItemId());
        static::assertInternalType('array', $putResponse->getResponse());
        static::assertNotEmpty($putResponse->getResponse());

        $singleItemResponseModel = $quickLookEntryPoint->getSingleItem($singleItemRequestModel);

        static::assertInstanceOf(SingleItemResponseModel::class, $singleItemResponseModel);
        static::assertInternalType('int', (int) $singleItemResponseModel->getItemId());
        static::assertInternalType('array', $singleItemResponseModel->getResponse());
        static::assertNotEmpty($singleItemResponseModel->getResponse());
    }
}