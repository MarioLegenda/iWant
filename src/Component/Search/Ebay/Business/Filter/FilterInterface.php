<?php

namespace App\Component\Search\Ebay\Business\Filter;

interface FilterInterface
{
    /**
     * @param array $entries
     * @return array
     */
    public function filter(array $entries): array;
}