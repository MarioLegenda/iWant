<?php

namespace App\Tests\Etsy;

use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Tests\Etsy\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class EtsyApiTest extends BasicSetup
{
    public function test_basic_query()
    {
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');

        $etsyApiEntryPoint->search($dataProvider->getEtsyApiModel());
    }
}