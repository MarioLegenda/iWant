<?php

namespace App\Tests\Bonanza;

use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Tests\Library\BasicSetup;

class BonanzaApiTest extends BasicSetup
{
    public function test_bonanza_api()
    {
        $bonanzaEntryPoint = $this->locator->get(BonanzaApiEntryPoint::class);
    }
}