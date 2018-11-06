<?php

namespace App\Tests\Web\DataProvider;

class DataProvider
{
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