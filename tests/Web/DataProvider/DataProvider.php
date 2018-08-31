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
     * @param string $keywords
     * @return iterable
     */
    public function getRequestArray(string $keywords = 'harry potter'): iterable
    {
        return [
            'keywords' => $keywords,
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

        return $itemFilters;
    }
}