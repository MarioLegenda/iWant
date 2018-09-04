<?php

namespace App\Tests\Ebay\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\GetCategoryInfoResponse;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Categories;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Category;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\CategoryRootItem;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
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
    /**
     * @param CategoryRootItem $rootItem
     */
    private function assertRootItem(CategoryRootItem $rootItem)
    {
        static::assertInternalType('string', $rootItem->getVersion());
        static::assertInternalType('string', $rootItem->getTimestamp());
        static::assertInternalType('string', $rootItem->getAck());
        static::assertInternalType('string', $rootItem->getNamespace());

        static::assertInternalType('int', $rootItem->getCategoryCount());
        static::assertInternalType('string', $rootItem->getCategoryVersion());
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