<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\App\Presentation\EntryPoint\NativeTaxonomyEntryPoint;
use App\App\Presentation\EntryPoint\SingleItemEntryPoint;
use App\Doctrine\Entity\Country;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\Infrastructure\Helper\TypedArray;
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

    public function test_get_native_taxonomies()
    {
        /** @var NativeTaxonomyEntryPoint $nativeTaxonomyEntryPoint */
        $nativeTaxonomyEntryPoint = $this->locator->get(NativeTaxonomyEntryPoint::class);

        $taxonomies = $nativeTaxonomyEntryPoint->getNativeTaxonomies();


    }
}