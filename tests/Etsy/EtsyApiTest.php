<?php

namespace App\Tests\Etsy;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ResponseItem\Results;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Tests\Etsy\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class EtsyApiTest extends BasicSetup
{
    public function test_basic_query()
    {
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $etsyApiEntryPoint->search($dataProvider->getEtsyApiModel());

        static::assertNotEmpty($responseModel->getCount());
        static::assertNotEmpty($responseModel->getResults());
        static::assertInstanceOf(Results::class, $responseModel->getResults());
    }
}