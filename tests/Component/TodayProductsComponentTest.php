<?php

namespace App\Tests\Component;

use App\Component\TodayProducts\Model\TodayProduct as TodayProductModel;
use App\Component\TodayProducts\TodayProductsComponent;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
use App\Tests\Library\BasicSetup;
use App\Web\Model\Request\TodayProductRequestModel;

class TodayProductsComponentTest extends BasicSetup
{
    public function test_todays_products_component()
    {
        $component = $this->locator->get(TodayProductsComponent::class);

        $products = $component->getTodaysProducts(new TodayProductRequestModel(
            Util::toDateTime(null, Util::getSimpleDateApplicationFormat())
        ));

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
            static::assertNotEmpty($product['itemId']);
            static::assertInternalType('string', $product['itemId']);

            static::assertNotEmpty($product['title']);
            static::assertInternalType('string', $product['title']);

            static::assertNotEmpty($product['price']);
            static::assertInternalType('string', $product['price']);

            static::assertNotEmpty($product['viewItemUrl']);
            static::assertInternalType('string', $product['viewItemUrl']);

            static::assertNotEmpty($product['shopName']);
            static::assertInternalType('string', $product['shopName']);

            static::assertNotEmpty($product['imageUrl']);
            static::assertInternalType('string', $product['imageUrl']);

            static::assertNotEmpty($product['marketplace']);
            static::assertTrue($marketplace->equals(MarketplaceType::fromValue($product['marketplace'])));
        }
    }
}