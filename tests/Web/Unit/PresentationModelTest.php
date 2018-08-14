<?php

namespace App\Tests\Web\Unit;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Tests\Etsy\DataProvider\DataProvider as EtsyDataProvider;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\EtsyModelFactory;

class PresentationModelTest extends BasicSetup
{
    public function test_etsy_presentation_creation()
    {
        /** @var EtsyModelFactory $etsyModelFactory */
        $etsyModelFactory = $this->locator->get(EtsyModelFactory::class);
        /** @var EtsyDataProvider $etsyModelProvider */
        $etsyModelProvider = $this->locator->get('data_provider.etsy_api');
        /** @var EtsyApiEntryPoint $etsyEntryPoint */
        $etsyEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);
        /** @var EtsyApiModel $etsyApiModel */
        $etsyApiModel = $etsyModelProvider->getEtsyApiModel();

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $etsyEntryPoint->search($etsyApiModel);

        static::assertInstanceOf(EtsyApiResponseModelInterface::class, $responseModel);


    }
}