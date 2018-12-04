<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Filter;

interface FilterInterface
{
    public function filter(array $results);
}