<?php

namespace App\Tests\Amazon\ProductAdvertisingApi;

use App\Amazon\Presentation\ProductAdvertising\EntryPoint\ProductAdvertisingEntryPoint;
use App\Tests\Library\BasicSetup;

class ProductAdvertisingTest extends BasicSetup
{
    public function test_search()
    {
        $productAdvertisinEntryPoint = $this->locator->get(ProductAdvertisingEntryPoint::class);

        
    }
}