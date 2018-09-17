<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\MarketplaceType;
use App\Tests\Library\BasicSetup;

class AppTest extends BasicSetup
{
    public function test_get_single_item()
    {
        /** @var SingleItemEntryPoint $singleItemEntryPoint */
        $singleItemEntryPoint = $this->locator->get(SingleItemEntryPoint::class);

        $dataProvider = $this->locator->get('data_provider.app');

        $singleItem = $singleItemEntryPoint->getSingleItem($dataProvider->createSingleItemRequestModel(
            '310344125882',
            MarketplaceType::fromValue('Ebay')
        ));

        static::assertInstanceOf(SingleProductItem::class, $singleItem);
    }
}