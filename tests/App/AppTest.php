<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\CountryEntryPoint;
use App\App\Presentation\EntryPoint\NativeTaxonomyEntryPoint;
use App\App\Presentation\EntryPoint\QuickLookEntryPoint;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Doctrine\Entity\Country;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
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
        /** @var QuickLookEntryPoint $singleItemEntryPoint */
        $singleItemEntryPoint = $this->locator->get(QuickLookEntryPoint::class);

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

    public function test_put_single_item_cache()
    {
        /** @var QuickLookEntryPoint $singleItemEntryPoint */
        $singleItemEntryPoint = $this->locator->get(QuickLookEntryPoint::class);

        $dataProvider = $this->locator->get('data_provider.app');

        /** @var SingleItemRequestModel $singleItemRequestModel */
        $singleItemRequestModel = $dataProvider->createSingleItemRequestModel('283106139038');

        /** @var SingleItemResponseModel $singleItemResponseModel */
        $singleItemResponseModel = $singleItemEntryPoint->putSingleItem($singleItemRequestModel);

        static::assertInstanceOf(SingleItemResponseModel::class, $singleItemResponseModel);
    }
}