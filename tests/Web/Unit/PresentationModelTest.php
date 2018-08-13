<?php

namespace App\Tests\Web\Unit;

use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\BonanzaModelFactory;
use App\Tests\Bonanza\DataProvider\DataProvider as BonanzaDataProvider;
use App\Web\Model\Response\UniformedResponseModel;

class PresentationModelTest extends BasicSetup
{
    public function test_ebay_presentation_creation()
    {
        /** @var BonanzaModelFactory $bonanzaModelFactory */
        $bonanzaModelFactory = $this->locator->get(BonanzaModelFactory::class);
        /** @var BonanzaDataProvider $bonanzaDataProvider */
        $bonanzaDataProvider = $this->locator->get('data_provider.bonanza_api');
        /** @var BonanzaApiEntryPoint $bonanzaEntryPoint */
        $bonanzaEntryPoint = $this->locator->get(BonanzaApiEntryPoint::class);
        /** @var BonanzaApiResponseModelInterface $responseModel */
        $bonanzaApiModel = $bonanzaDataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);
        /** @var BonanzaModelFactory $bonanzaModelFactory */
        $bonanzaModelFactory = $this->locator->get(BonanzaModelFactory::class);

        /** @var BonanzaApiResponseModelInterface $responseModel */
        $responseModel = $bonanzaEntryPoint->search($bonanzaApiModel);

        static::assertInstanceOf(BonanzaApiResponseModelInterface::class, $responseModel);

        /** @var UniformedResponseModel $presentationModels */
        $presentationModels = $bonanzaModelFactory->createModels($responseModel);
    }
}