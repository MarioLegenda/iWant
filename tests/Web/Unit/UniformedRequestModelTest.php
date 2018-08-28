<?php

namespace App\Tests\Web\Unit;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Tests\Library\BasicSetup;
use App\Web\Model\Request\RequestItemFilter;
use App\Web\Model\Request\UniformedRequestModel;
use App\Web\Model\Type\TypeMap;

class UniformedRequestModelTest extends BasicSetup
{
    public function test_uniformed_request_model_creation()
    {
        $requestArray = [
            'keywords' => 'some keyword',
            'itemFilters' => $this->createItemFilters(),
        ];

        $uniformedRequestModel = new UniformedRequestModel(
            $requestArray['keywords'],
            $requestArray['itemFilters']
        );

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
    /**
     * @return array
     */
    private function createItemFilters(): array
    {
        $itemFilters = [];

        $itemFilters[] = [
            'filterType' => 'LowestPrice',
            'data' => [],
        ];

        $itemFilters[] = [
            'filterType' => 'HighestPrice',
            'data' => [],
        ];

        $itemFilters[] = [
            'filterType' => 'Used',
            'data' => [],
        ];

        $itemFilters[] = [
            'filterType' => 'HighQuality',
            'data' => [],
        ];

        $itemFilters[] = [
            'filterType' => 'Handmade',
            'data' => [],
        ];

        $itemFilters[] = [
            'filterType' => 'ShipsToCountry',
            'data' => [
                'name' => 'Country',
                'alpha3Code' => 'Asc'
            ],
        ];

        $itemFilters[] = [
            'filterType' => 'PriceRange',
            'data' => [
                'minPrice' => 5,
                'maxPrice' => 10,
            ],
        ];

        return $itemFilters;
    }
}