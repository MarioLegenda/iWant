<?php

namespace App\Tests\Web;

use App\Tests\Library\BasicSetup;
use App\Tests\Web\DataProvider\DataProvider as UniformedRequestDataProvider;

class UniformedSearchTest extends BasicSetup
{
    public function test_uniformed_search()
    {
        /** @var UniformedRequestDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.uniformed_request');
    }
}