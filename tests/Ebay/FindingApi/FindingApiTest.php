<?php

namespace App\Tests\Ebay\FindingApi;

use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class FindingApiTest extends BasicSetup
{
    public function test_finding_api()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $model = $dataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);

        $findingApiEntryPoint->query($model);
    }
}