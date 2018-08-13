<?php

namespace App\Tests\Web;

use App\Tests\Library\BasicSetup;
use App\Tests\Web\DataProvider\DataProvider as UniformedRequestDataProvider;
use App\Web\Controller\UniformedRequestController;
use App\Web\Model\Request\EbayModels;
use App\Web\Model\Request\UniformedRequestModel;
use App\Web\Model\Response\UniformedResponseModel;

class UniformedSearchTest extends BasicSetup
{
    public function test_uniformed_test()
    {
        /** @var UniformedRequestDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.uniformed_request');
        /** @var UniformedRequestController $uniformedRequestController */
        $uniformedRequestController = $this->locator->get(UniformedRequestController::class);

        $etsyApiModel = $dataProvider->getEtsyDataProvider()->getEtsyApiModel();
        $findingApiModel = $dataProvider->getEbayDataProvider()->getFindItemsByKeywordsData([
            'boots, mountain',
        ]);
        $bonanzaApiModel = $dataProvider->getBonanzaDataProvider()->getFindItemsByKeywordsData([
            'boots, mountain',
        ]);
    }
}