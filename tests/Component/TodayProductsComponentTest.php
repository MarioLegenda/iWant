<?php

namespace App\Tests\Component;

use App\Component\TodayProducts\Model\TodayProduct as TodayProductModel;
use App\Component\TodayProducts\TodayProductsComponent;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Tests\Library\BasicSetup;
use App\Component\Request\Model\TodayProduct as RequestTodayProductModel;

class TodayProductsComponentTest extends BasicSetup
{
    public function test_todays_products_component()
    {
        $component = $this->locator->get(TodayProductsComponent::class);

        $products = $component->getTodaysProducts(new RequestTodayProductModel(new \DateTime()));

        static::assertArrayHasKey('ebay', $products);
        static::assertArrayHasKey('etsy', $products);

        $this->assertMarketplaceTodayProduct($products['ebay'], MarketplaceType::fromValue('Ebay'));
        $this->assertMarketplaceTodayProduct($products['etsy'], MarketplaceType::fromValue('Etsy'));
    }
    /**
     * @param iterable $products
     * @param MarketplaceType|TypeInterface $marketplace
     */
    private function assertMarketplaceTodayProduct(
        iterable $products,
        MarketplaceType $marketplace
    ) {
        /** @var TodayProductModel $product */
        foreach ($products as $product) {
            static::assertInstanceOf(TodayProductModel::class, $product);

            static::assertNotEmpty($product->getItemId());
            static::assertInternalType('string', $product->getItemId());

            static::assertNotEmpty($product->getTitle());
            static::assertInternalType('string', $product->getTitle());

            static::assertNotEmpty($product->getPrice());
            static::assertInternalType('string', $product->getPrice());

            static::assertNotEmpty($product->getViewItemUrl());
            static::assertInternalType('string', $product->getViewItemUrl());

            static::assertNotEmpty($product->getShopName());
            static::assertInternalType('string', $product->getShopName());

            static::assertNotEmpty($product->getImageUrl());
            static::assertInternalType('string', $product->getImageUrl());

            static::assertNotEmpty($product->getMarketplace());
            static::assertTrue($marketplace->equals($product->getMarketplace()));
        }
    }
}