<?php

namespace App\Tests\Component;

use App\Component\TodayProducts\Model\Image;
use App\Component\TodayProducts\Model\TodayProduct as TodayProductModel;
use App\Component\TodayProducts\TodayProductsComponent;
use App\Ebay\Library\Information\GlobalIdInformation;
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
            static::assertInternalType('array', $product['title']);

            $title = $product['title'];
            static::assertInternalType('string', $title['truncated']);
            static::assertNotEmpty($title['truncated']);

            static::assertInternalType('string', $title['original']);
            static::assertNotEmpty($title['original']);

            static::assertNotEmpty($product['price']);
            static::assertInternalType('array', $product['price']);

            $price = $product['price'];
            static::assertInternalType('string', $price['currency']);
            static::assertInternalType('string', $price['price']);

            static::assertNotEmpty($product['viewItemUrl']);
            static::assertInternalType('string', $product['viewItemUrl']);

            static::assertNotEmpty($product['shopName']);
            static::assertInternalType('string', $product['shopName']);

            static::assertNotEmpty($product['image']);
            static::assertInternalType('array', $product['image']);

            /** @var Image $image */
            $image = $product['image'];

            if ($image['url'] !== 'NaN') {
                static::assertNotEquals('Nan', $image['url']);
                static::assertInternalType('string', $image['width']);
                static::assertInternalType('string', $image['height']);
            }

            static::assertNotEmpty($product['marketplace']);
            static::assertTrue($marketplace->equals(MarketplaceType::fromValue($product['marketplace'])));

            if ($product['marketplace'] === 'Ebay') {
                static::assertNotEmpty($product['globalId']);
                static::assertTrue(GlobalIdInformation::instance()->has($product['globalId']));
            }
        }
    }
}