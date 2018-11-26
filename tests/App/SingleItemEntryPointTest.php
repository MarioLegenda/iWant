<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
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
            '382619664080',
            'en'
        );

        $singleItemArray = $singleItemEntryPoint->getSingleItem($singleItemRequestModel);

        static::assertNotEmpty($singleItemArray);
        static::assertInternalType('array', $singleItemArray);
    }
}