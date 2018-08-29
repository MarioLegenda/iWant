<?php

namespace App\Tests\Bonanza;

use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Bonanza\Library\Response\ResponseItem\FindItemsByKeywordsResponse;
use App\Bonanza\Library\Response\ResponseItem\Item;
use App\Bonanza\Library\Response\ResponseItem\ListingInfo;
use App\Bonanza\Library\Response\ResponseItem\RootItem;
use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Tests\Bonanza\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class BonanzaApiTest extends BasicSetup
{
    public function test_bonanza_api()
    {
        /** @var BonanzaApiEntryPoint $bonanzaEntryPoint */
        $bonanzaEntryPoint = $this->locator->get(BonanzaApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.bonanza_api');

        $model = $dataProvider->getFindItemsByKeywordsData('boots for mountain');

        $bonanzaApiModel = $bonanzaEntryPoint->search($model);

        static::assertInstanceOf(BonanzaApiResponseModelInterface::class, $bonanzaApiModel);

        static::assertInstanceOf(RootItem::class, $bonanzaApiModel->getRootItem());

        $rootItem = $bonanzaApiModel->getRootItem();

        static::assertInternalType('string', $rootItem->getAck());
        static::assertInternalType('string', $rootItem->getTimestamp());
        static::assertInternalType('string', $rootItem->getVersion());
        static::assertInstanceOf(TypeInterface::class, $rootItem->getResponseType());

        $findItemsByKeywordsResponse = $bonanzaApiModel->getFindItemsByKeywordsResponse();

        static::assertInstanceOf(FindItemsByKeywordsResponse::class, $findItemsByKeywordsResponse);

        static::assertInternalType('int', $findItemsByKeywordsResponse->getTotalEntries());
        static::assertInstanceOf(TypedArray::class, $findItemsByKeywordsResponse->getItems());

        $items = $findItemsByKeywordsResponse->getItems();

        /** @var Item $item */
        foreach ($items as $item) {
            static::assertInstanceOf(Item::class, $item);

            static::assertInternalType('int', $item->getItemId());
            static::assertInternalType('string', $item->getViewItemUrl());
            static::assertInternalType('string', $item->getGalleryUrl());
            static::assertInternalType('string', $item->getTitle());
            static::assertInternalType('string', $item->getDescription());

            $listingInfo = $item->getListingInfo();

            static::assertInstanceOf(ListingInfo::class, $listingInfo);

            static::assertInternalType('string', $listingInfo->getPrice());
            static::assertInternalType('string', $listingInfo->getBuyItNowPrice());
            static::assertInternalType('string', $listingInfo->getLastChangeTime());
            static::assertInternalType('string', $listingInfo->getListingType());
            static::assertInternalType('string', $listingInfo->getStartTime());
        }
    }
}