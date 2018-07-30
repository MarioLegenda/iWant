<?php

namespace App\Tests\FindingApi;

use App\Ebay\Presentation\EntryPoint\FindingApiEntryPoint;
use App\Tests\Library\BasicSetup;

class FindingApiTest extends BasicSetup
{
    public function test_finding_api()
    {
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
    }
}