<?php

namespace App\Tests\Bonanza;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Tests\Bonanza\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class BonanzaApiTest extends BasicSetup
{
    public function test_bonanza_api()
    {
        /** @var BonanzaApiEntryPoint $bonanzaEntryPoint */
        $bonanzaEntryPoint = $this->locator->get(BonanzaApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.bonanza_api');

        $model = $dataProvider->getFindItemsByKeywordsData([
            'boots, mountain',
        ]);

        $bonanzaEntryPoint->search($model);
    }
}