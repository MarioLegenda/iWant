<?php

namespace App\Tests\Ebay\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\GetShippingCostsResponse;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponse;
use App\Ebay\Library\Response\ShoppingApi\Json\Item;
use App\Ebay\Library\Response\ShoppingApi\Json\Root;
use App\Ebay\Library\Response\ShoppingApi\Json\SellerInfo;
use App\Ebay\Library\Response\ShoppingApi\Json\Shipping\ShippingSummary;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Tests\Ebay\ShoppingApi\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class ShoppingApiTest extends BasicSetup
{
    public function test_get_user_profile()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.shopping_api');
        /** @var ShoppingApiEntryPoint $shoppingApiEntryPoint */
        $shoppingApiEntryPoint = $this->locator->get(ShoppingApiEntryPoint::class);

        /** @var GetUserProfileResponse $responseModel */
        $responseModel = $shoppingApiEntryPoint->getUserProfile($dataProvider->createGetUserProfileModel('paulsanglingsupplies'));

        static::assertInstanceOf(GetUserProfileResponse::class, $responseModel);
        static::assertNotEmpty($responseModel->toArray());
        static::assertInternalType('array', $responseModel->toArray());

        /** @var Root $rootItem */
        $rootItem = $responseModel->getRoot();

        $this->assertRootItem($rootItem);
    }

    public function test_single_item()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.shopping_api');
        /** @var ShoppingApiEntryPoint $shoppingApiEntryPoint */
        $shoppingApiEntryPoint = $this->locator->get(ShoppingApiEntryPoint::class);

        /** @var GetSingleItemResponse $responseModel */
        $responseModel = $shoppingApiEntryPoint->getSingleItem($dataProvider->createGetSingleItemModel());

        static::assertInstanceOf(GetSingleItemResponse::class, $responseModel);
        static::assertNotEmpty($responseModel->toArray());
        static::assertInternalType('array', $responseModel->toArray());

        /** @var Root $rootItem */
        $rootItem = $responseModel->getRoot();

        $this->assertRootItem($rootItem);

        /** @var Item $singleItem */
        $singleItem = $responseModel->getSingleItem();

        static::assertInstanceOf(Item::class, $singleItem);

        $this->assertItem($singleItem);
    }

    public function test_get_shipping_costs()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.shopping_api');
        /** @var ShoppingApiEntryPoint $shoppingApiEntryPoint */
        $shoppingApiEntryPoint = $this->locator->get(ShoppingApiEntryPoint::class);

        /** @var GetShippingCostsResponse $responseModel */
        $responseModel = $shoppingApiEntryPoint->getShippingCosts($dataProvider->createGetShippingCostsModel());

        static::assertInstanceOf(GetShippingCostsResponse::class, $responseModel);
        static::assertNotEmpty($responseModel->toArray());

        static::assertInternalType('array', $responseModel->toArray());
        static::assertNotEmpty($responseModel->toArray());
    }
    /**
     * @param Item $singleItem
     */
    private function assertItem(Item $singleItem)
    {
        static::assertInternalType('string', $singleItem->getItemId());
        static::assertInternalType('string', $singleItem->getStartTime());
        static::assertInternalType('string', $singleItem->getEndTime());
        static::assertInternalType('string', $singleItem->getDescription());
        static::assertInternalType('string', $singleItem->getGalleryUrl());
        static::assertInternalType('string', $singleItem->getListingType());
        static::assertInternalType('string', $singleItem->getQuantity());
        static::assertInternalType('string', $singleItem->getLocation());
        static::assertInternalType('array', $singleItem->getPaymentMethods());
        static::assertInternalType('array', $singleItem->getPictureUrl());
        static::assertInternalType('string', $singleItem->getViewItemUrlForNaturalSearch());
        static::assertInternalType('bool', $singleItem->getBestOfferEnabled());

        static::assertInstanceOf(SellerInfo::class, $singleItem->getSeller());

        $seller = $singleItem->getSeller();

        static::assertInternalType('string', $seller->getFeedbackScore());
        static::assertInternalType('string', $seller->getSellerId());
        static::assertInternalType('string', $seller->getFeedbackRatingStar());
        static::assertInternalType('string', $seller->getPositiveFeedbackPercent());

        static::assertInternalType('string', $singleItem->getListingStatus());
        static::assertInternalType('string', $singleItem->getQuantitySold());
        static::assertInternalType('array', $singleItem->getShipsToLocations());
        static::assertInternalType('string', $singleItem->getTitle());
    }
    /**
     * @param Root $rootItem
     */
    private function assertRootItem(Root $rootItem)
    {
        static::assertInternalType('string', $rootItem->getVersion());
        static::assertInternalType('string', $rootItem->getTimestamp());
        static::assertInternalType('string', $rootItem->getAck());
    }
}