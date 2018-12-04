<?php

namespace App\Component\Search\Ebay\Business\Filter;

interface FilterApplierInterface
{
    /**
     * @param FilterInterface $filter
     * @param int $priority
     * @return void
     */
    public function add(FilterInterface $filter, int $priority = 0);
    /**
     * @return bool
     */
    public function hasFilters(): bool;
    /**
     * @param array $entries
     * @return array
     */
    public function apply(array $entries): array;
}