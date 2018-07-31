<?php

namespace App\Tests\FindingApi;

use App\Ebay\Presentation\EntryPoint\FindingApiEntryPoint;
use App\Tests\FindingApi\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class FindingApiTest extends BasicSetup
{
    public function test_finding_api()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $model = $dataProvider->getFindItemsByKeywordsData();

        $findingApiEntryPoint->query($model);
    }
}