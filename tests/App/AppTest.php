<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\App\Presentation\EntryPoint\NativeTaxonomyEntryPoint;
use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\Doctrine\Entity\Country;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Tests\Library\BasicSetup;

class AppTest extends BasicSetup
{
    public function test_get_countries()
    {
        /** @var CountryEntryPoint $countryEntryPoint */
        $countryEntryPoint = $this->locator->get(CountryEntryPoint::class);

        /** @var Country[]|TypedArray $countries */
        $countries = $countryEntryPoint->getCountries();

        static::assertNotEmpty($countries);
        static::assertGreaterThan(1, count($countries));

        foreach ($countries as $country) {
            static::assertInstanceOf(Country::class, $country);
        }
    }

    public function test_options_single_item()
    {
        /** @var SingleItemEntryPoint $singleItemEntryPoint */
        $singleItemEntryPoint = $this->locator->get(SingleItemEntryPoint::class);

        $itemId = (string) rand(999999, 999999999);

        $dataProvider = $this->locator->get('data_provider.app');

        $singleItemOptionsResponse = $singleItemEntryPoint->optionsCheckSingleItem(
            $dataProvider->createFakeSingleItemOptionsModel($itemId)
        );

        static::assertInstanceOf(SingleItemOptionsResponse::class, $singleItemOptionsResponse);
        static::assertEquals('PUT', $singleItemOptionsResponse->getMethod());
        static::assertInternalType('string', $singleItemOptionsResponse->getRoute());
        static::assertEquals($itemId, $singleItemOptionsResponse->getItemId());
    }
}