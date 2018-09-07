<?php

namespace App\Tests\Component;

use App\Doctrine\Repository\TodaysKeywordRepository;
use App\Library\Representation\NormalizedCategoryRepresentation;
use App\Tests\Library\BasicSetup;

class TodayProductsComponentTest extends BasicSetup
{
    public function test_todays_products_component()
    {
        $this->createKeywordsSpread();
    }

    private function createKeywordsSpread()
    {
        /** @var TodaysKeywordRepository $todaysKeywordRepository */
        $todaysKeywordRepository = $this->locator->get(TodaysKeywordRepository::class);

        $temp = new NormalizedCategoryRepresentation();
    }
}