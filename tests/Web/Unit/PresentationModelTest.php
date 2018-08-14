<?php

namespace App\Tests\Web\Unit;

use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Bonanza\Presentation\Model\BonanzaApiModel;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Tests\Etsy\DataProvider\DataProvider as EtsyDataProvider;
use App\Tests\Bonanza\DataProvider\DataProvider as BonanzaDataProvider;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\BonanzaModelFactory;
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

    public function test_bonanza_presentation_creation()
    {
        /** @var BonanzaModelFactory $modelFactory */
        $modelFactory = $this->locator->get(BonanzaModelFactory::class);
        /** @var BonanzaDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.bonanza_api');
        /** @var BonanzaApiEntryPoint $entryPoint */
        $entryPoint = $this->locator->get(BonanzaApiEntryPoint::class);
        /** @var BonanzaApiModel $apiModel */
        $apiModel = $dataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);

        /** @var BonanzaApiResponseModelInterface $responseModel */
        $responseModel = $entryPoint->search($apiModel);

        static::assertInstanceOf(BonanzaApiResponseModelInterface::class, $responseModel);

        $presentationModels = $modelFactory->createModels($responseModel);

        dump($presentationModels);
        die();
    }
}