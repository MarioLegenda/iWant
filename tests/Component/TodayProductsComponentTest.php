<?php

namespace App\Tests\Component;

use App\Component\Request\Model\TodayProduct;
use App\Component\TodayProducts\TodayProductsComponent;
use App\Tests\Library\BasicSetup;

class TodayProductsComponentTest extends BasicSetup
{
    public function test_todays_products_component()
    {
        $component = $this->locator->get(TodayProductsComponent::class);

        $component->getTodaysProducts(new TodayProduct(new \DateTime()));
    }
}