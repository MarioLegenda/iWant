<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Filter;

class FilterApplier implements FilterApplierInterface
{
    private $filters = [];

    public function add(FilterInterface $filter, int $priority = 0): void
    {
        $this->filters[] = [
            'name' => get_class($filter),
            'priority' => $priority,
        ];
    }

    public function apply(): array
    {

    }
}