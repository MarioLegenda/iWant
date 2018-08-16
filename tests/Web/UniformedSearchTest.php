<?php

namespace App\Tests\Web;

use App\Tests\Library\BasicSetup;
use App\Tests\Web\DataProvider\DataProvider as UniformedRequestDataProvider;
use App\Web\Model\Response\UniformedResponseModel;
use App\Web\UniformedEntryPoint;

class UniformedSearchTest extends BasicSetup
{
    public function test_uniformed_search_lowest_price()
    {
        /** @var UniformedRequestDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.uniformed_request');
        /** @var UniformedEntryPoint $uniformedEntryPoint */
        $uniformedEntryPoint = $this->locator->get(UniformedEntryPoint::class);

        $uniformedRequestModel = $dataProvider->getUniformedRequestModel('lowest_price');

        $groupedModels = $uniformedEntryPoint->getPresentationModels($uniformedRequestModel);

        static::assertGreaterThan(1, count($groupedModels));

        $previous = $groupedModels[0]->getPrice();
        /** @var UniformedResponseModel $presentationModel */
        foreach ($groupedModels as $numericKey => $presentationModel) {
            if ($numericKey === 0) {
                continue;
            }

            $this->assertLowerThanOrEquals($presentationModel->getPrice(), $previous);

            $previous = $presentationModel->getPrice();
        }
    }

    public function test_uniformed_search_highest_price()
    {
        /** @var UniformedRequestDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.uniformed_request');
        /** @var UniformedEntryPoint $uniformedEntryPoint */
        $uniformedEntryPoint = $this->locator->get(UniformedEntryPoint::class);

        $uniformedRequestModel = $dataProvider->getUniformedRequestModel('highest_price');

        $groupedModels = $uniformedEntryPoint->getPresentationModels($uniformedRequestModel);

        static::assertGreaterThan(1, count($groupedModels));

        $previous = $groupedModels[0]->getPrice();
        /** @var UniformedResponseModel $presentationModel */
        foreach ($groupedModels as $numericKey => $presentationModel) {
            if ($numericKey === 0) {
                continue;
            }

            $this->assertGreaterThanOrEquals($presentationModel->getPrice(), $previous);

            $previous = $presentationModel->getPrice();
        }
    }
}