<?php

namespace App\Tests\Component;

use App\Component\TodayProducts\Model\TodayProduct as TodayProductModel;
use App\Component\TodayProducts\TodayProductsComponent;
use App\Tests\Library\BasicSetup;
use App\Component\Request\Model\TodayProduct as RequestTodayProductModel;

class TodayProductsComponentTest extends BasicSetup
{
    public function test_todays_products_component()
    {
        $component = $this->locator->get(TodayProductsComponent::class);

        /** @var TodayProductModel[] $products */
        $products = $component->getTodaysProducts(new RequestTodayProductModel(new \DateTime()));

        static::assertEquals(8, count($products));
    }
}