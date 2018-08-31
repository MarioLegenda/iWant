<?php

namespace App\Tests\Web\Unit;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Tests\Library\BasicSetup;
use App\Tests\Web\DataProvider\DataProvider;
use App\Web\UniformedEntryPoint;

class UniformedSearchTest extends BasicSetup
{
    public function test_uniformed_search()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.uniformed_request');
        /** @var UniformedEntryPoint $uniformedRequestEntryPoint */
        $uniformedRequestEntryPoint = $this->locator->get(UniformedEntryPoint::class);

        $uniformedRequest = $dataProvider->getUniformedRequestFullModel();

        $models = $uniformedRequestEntryPoint->getPresentationModels($uniformedRequest);

        static::assertInstanceOf(TypedArray::class, $models);
    }
}