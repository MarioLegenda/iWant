<?php

namespace App\Tests\Web\Unit;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Tests\Library\BasicSetup;
use App\Web\Model\Request\RequestItemFilter;
use App\Web\Model\Type\TypeMap;

class UniformedRequestModelTest extends BasicSetup
{
    public function test_uniformed_request_model_creation()
    {
        $dataProvider = $this->locator->get('data_provider.uniformed_request');

        $requestArray = $dataProvider->getRequestArray();
        $uniformedRequestModel = $dataProvider->getUniformedRequestFullModel($requestArray);

        $keywords = $uniformedRequestModel->getKeywords();
        $itemFilters = $uniformedRequestModel->getItemFilters();

        static::assertEquals($requestArray['keywords'], $keywords);

        /** @var RequestItemFilter $itemFilter */
        foreach ($itemFilters as $itemFilter) {
            static::assertInstanceOf(TypeInterface::class, $itemFilter->getType());

            /** @var TypeInterface $type */
            $type = $itemFilter->getType();

            static::assertInternalType('string', TypeMap::instance()->getTypeMapFor($type->getValue()));
        }

        foreach ($requestArray['itemFilters'] as $itemFilter) {
            $filterType = $itemFilter['filterType'];

            $typeFound = false;
            /** @var RequestItemFilter $requestItemFilter */
            foreach ($uniformedRequestModel->getItemFilters() as $requestItemFilter) {
                $type = $requestItemFilter->getType();

                if ($filterType === $type->getValue()) {
                    $typeFound = true;
                }
            }

            static::assertTrue($typeFound);
        }
    }
}