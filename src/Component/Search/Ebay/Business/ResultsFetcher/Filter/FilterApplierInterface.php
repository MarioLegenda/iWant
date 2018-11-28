<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Filter;

interface FilterApplierInterface
{
    /**
     * @return array
     */
    public function apply(): array;
    /**
     * @param FilterInterface $filter
     * @param int $priority
     */
    public function add(FilterInterface $filter, int $priority = 0): void;
}