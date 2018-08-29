<?php

namespace App\Tests\Web\DataProvider;

use App\Web\Model\Request\UniformedRequestModel;

class DataProvider
{
    /**
     * @param array $requestArray
     * @return UniformedRequestModel
     */
    public function getUniformedRequestFullModel(array $requestArray = null): UniformedRequestModel
    {
        if (!is_array($requestArray)) {
            $requestArray = $this->getRequestArray();
        }

        return new UniformedRequestModel(
            $requestArray['keywords'],
            $requestArray['itemFilters']
        );
    }
    /**
     * @return iterable
     */
    public function getRequestArray(): iterable
    {
        return [
            'keywords' => 'some keyword',
            'itemFilters' => $this->createItemFilters(),
        ];
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