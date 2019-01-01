<?php

namespace App\Tests\Ebay\ShoppingApi;

use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\ShoppingApi\GetCategoryInfoResponse;
use App\Ebay\Library\Response\ShoppingApi\GetShippingCostsResponse;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Categories;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Category;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\CategoryRootItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ItemSpecifics;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\PriceInfo;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\RootItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\SellerItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\InternationalShippingServiceOption;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingDetails;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCostSummary;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\SingleItem;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Tests\Ebay\ShoppingApi\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class ShoppingApiTest extends BasicSetup
{
    public function test_get_category_info()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.shopping_api');
        /** @var ShoppingApiEntryPoint $shoppingApiEntryPoint */
        $shoppingApiEntryPoint = $this->locator->get(ShoppingApiEntryPoint::class);

        /** @var GetCategoryInfoResponse $response */
        $response = $shoppingApiEntryPoint->getCategoryInfo($dataProvider->createGetCategoryInfoModel());

        static::assertInstanceOf(GetCategoryInfoResponse::class, $response);

        /** @var CategoryRootItem $rootItem */
        $rootItem = $response->getRoot();

        static::assertInstanceOf(CategoryRootItem::class, $rootItem);

        $this->assertRootItem($rootItem);
        $this->assertCategoryRootItem($rootItem);

        $categories = $response->getCategories();

        static::assertInstanceOf(Categories::class, $categories);

        /** @var Category $category */
        foreach ($categories as $category) {
            static::assertInstanceOf(Category::class, $category);

            $this->assertCategory($category);
        }

        static::assertInternalType('array', $response->toArray());
        static::assertNotEmpty($response->toArray());
    }

    public function test_get_category_info_with_multiple_global_ids()
    {
        $globalIds = [
            GlobalIdInformation::EBAY_DE,
            GlobalIdInformation::EBAY_GB,
        ];

        foreach ($globalIds as $globalId) {
            /** @var DataProvider $dataProvider */
            $dataProvider = $this->locator->get('data_provider.shopping_api');
            /** @var ShoppingApiEntryPoint $shoppingApiEntryPoint */
            $shoppingApiEntryPoint = $this->locator->get(ShoppingApiEntryPoint::class);

            /** @var GetCategoryInfoResponse $response */
            $response = $shoppingApiEntryPoint->getCategoryInfo($dataProvider->createGetCategoryInfoModel($globalId));

            /** @var GetCategoryInfoResponse $response */
            $response = $shoppingApiEntryPoint->getCategoryInfo($dataProvider->createGetCategoryInfoModel());

            static::assertInstanceOf(GetCategoryInfoResponse::class, $response);

            /** @var CategoryRootItem $rootItem */
            $rootItem = $response->getRoot();

            static::assertInstanceOf(CategoryRootItem::class, $rootItem);

            $this->assertRootItem($rootItem);
            $this->assertCategoryRootItem($rootItem);

            $categories = $response->getCategories();

            static::assertInstanceOf(Categories::class, $categories);

            /** @var Category $category */
            foreach ($categories as $category) {
                static::assertInstanceOf(Category::class, $category);

                $this->assertCategory($category);
            }

            static::assertInternalType('array', $response->toArray());
            static::assertNotEmpty($response->toArray());
        }
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

        /** @var RootItem $rootItem */
        $rootItem = $responseModel->getRoot();

        $this->assertRootItem($rootItem);

        /** @var SingleItem $singleItem */
        $singleItem = $responseModel->getSingleItem();

        static::assertInstanceOf(SingleItem::class, $singleItem);

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

        /** @var \App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary $shippingCostsSummary */
        $shippingCostsSummary = $responseModel->getShippingCostsSummary();

        static::assertInstanceOf(\App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary::class, $shippingCostsSummary);

        $this->assertShippingCostsSummary($shippingCostsSummary);

        static::assertInternalTypeOrNull('bool', $responseModel->isEligibleForPickupInStore());

        static::assertInstanceOf(ShippingDetails::class, $responseModel->getShippingDetails());

        $shippingDetails = $responseModel->getShippingDetails();

        static::assertInternalTypeOrNull('float', $shippingDetails->getCashOnDeliveryCost());
        static::assertInstanceOfOrNull(BasePrice::class, $shippingDetails->getInsuranceCost());
        static::assertInternalTypeOrNull('array', $shippingDetails->getExcludeShipToLocations());
        static::assertInstanceOfOrNull(TypeInterface::class, $shippingDetails->getInsuranceOption());
        static::assertInstanceOfOrNull(BasePrice::class, $shippingDetails->getInternationalInsuranceCost());
        static::assertInstanceOfOrNull(TypeInterface::class, $shippingDetails->getInternationalInsuranceOption());
        static::assertInternalTypeOrNull('array', $shippingDetails->getInternationalShippingServiceOption());
    }
    /**
     * @param \App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary $shippingCostsSummary
     */
    private function assertShippingCostsSummary(\App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary $shippingCostsSummary)
    {
        static::assertInternalType('string', $shippingCostsSummary->getShippingServiceName());
        static::assertInstanceOf(BasePrice::class, $shippingCostsSummary->getShippingServiceCost());
        static::assertInstanceOf(BasePrice::class, $shippingCostsSummary->getListedShippingServiceCost());
        static::assertInternalType('string', $shippingCostsSummary->getShippingType());
        static::assertInstanceOfOrNull(BasePrice::class, $shippingCostsSummary->getImportCharge());
        static::assertInstanceOfOrNull(BasePrice::class, $shippingCostsSummary->getInsuranceCost());
        static::assertInstanceOfOrNull(TypeInterface::class, $shippingCostsSummary->getInsuranceOption());
    }
    /**
     * @param SingleItem $singleItem
     */
    private function assertItem(SingleItem $singleItem)
    {
        static::assertInternalType('string', $singleItem->getItemId());
        static::assertInternalType('string', $singleItem->getStartTime());
        static::assertInternalType('string', $singleItem->getEndTime());
        static::assertInternalType('string', $singleItem->getDescription());
        static::assertInternalType('string', $singleItem->getGalleryUrl());
        static::assertInternalType('string', $singleItem->getListingType());
        static::assertInternalType('string', $singleItem->getQuantity());
        static::assertInternalType('string', $singleItem->getLocation());
        static::assertInternalType('string', $singleItem->getPaymentMethods());
        static::assertInternalType('string', $singleItem->getPictureUrl());
        static::assertInternalType('string', $singleItem->getViewItemUrlForNaturalSearch());
        static::assertInternalType('string', $singleItem->getPrimaryCategoryName());
        static::assertInternalType('string', $singleItem->getPrimaryCategoryId());
        static::assertInternalType('bool', $singleItem->getBestOfferEnabled());

        static::assertInstanceOf(SellerItem::class, $singleItem->getSeller());

        $seller = $singleItem->getSeller();

        static::assertInternalType('string', $seller->getFeedbackScore());
        static::assertInternalType('string', $seller->getUserId());
        static::assertInternalType('string', $seller->getFeedbackRatingStart());
        static::assertInternalType('string', $seller->getPositiveFeedbackPercent());

        $priceInfo = $singleItem->getPriceInfo();

        static::assertInstanceOf(PriceInfo::class, $priceInfo);
        static::assertInternalType('string', $priceInfo->getCurrentPrice());
        static::assertInternalType('string', $priceInfo->getConvertedCurrentPrice());
        static::assertInternalType('string', $priceInfo->getConvertedCurrentPriceId());
        static::assertInternalType('string', $priceInfo->getCurrentPriceId());

        static::assertInternalType('string', $singleItem->getListingStatus());
        static::assertInternalType('string', $singleItem->getQuantitySold());
        static::assertInternalType('array', $singleItem->getShipsToLocations());
        static::assertInternalType('string', $singleItem->getSite());
        static::assertInternalType('string', $singleItem->getTimeLeft());
        static::assertInternalType('string', $singleItem->getTitle());

        $shippingCostSummary = $singleItem->getShippingCostSummary();

        static::assertInstanceOf(ShippingCostSummary::class, $shippingCostSummary);
        static::assertInternalType('string', $shippingCostSummary->getShippingType());

        $itemSpecifics = $singleItem->getItemSpecifics();

        static::assertInstanceOf(ItemSpecifics::class, $itemSpecifics);

        $nameValueList = $itemSpecifics->getNameValueList();
        static::assertGreaterThan(1, count($nameValueList));

        foreach ($nameValueList as $item) {
            static::assertNotEmpty($item);
            static::assertInternalType('array', $item);
        }
    }
    /**
     * @param CategoryRootItem $rootItem
     */
    private function assertCategoryRootItem(CategoryRootItem $rootItem)
    {
        static::assertInternalType('int', $rootItem->getCategoryCount());
        static::assertInternalType('string', $rootItem->getCategoryVersion());
    }
    /**
     * @param RootItem $rootItem
     */
    private function assertRootItem(RootItem $rootItem)
    {
        static::assertInternalType('string', $rootItem->getVersion());
        static::assertInternalType('string', $rootItem->getTimestamp());
        static::assertInternalType('string', $rootItem->getAck());
        static::assertInternalType('string', $rootItem->getNamespace());
    }
    /**
     * @param Category $category
     */
    private function assertCategory(Category $category)
    {
        static::assertInternalType('string', $category->getCategoryId());
        static::assertInternalType('string', $category->getCategoryIdPath());
        static::assertInternalType('string', $category->getCategoryName());
        static::assertInternalType('string', $category->getCategoryNamePath());
        static::assertInternalType('string', $category->getCategoryParentId());
        static::assertInternalType('int', $category->getCategoryLevel());
        static::assertInternalType('bool', $category->getLeafCategory());
    }
}