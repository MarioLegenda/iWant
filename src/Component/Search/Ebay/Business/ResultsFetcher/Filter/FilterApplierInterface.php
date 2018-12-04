<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Filter;

interface FilterApplierInterface
{
    /**
     * @param array $results
     * @return array
     */
    public function apply(array $results): array;
    /**
     * @param FilterInterface $filter
     * @param int $priority
     */
    public function add(FilterInterface $filter, int $priority = 0): void;
    /**
     * @return bool
     */
    public function hasFilters(): bool;
}