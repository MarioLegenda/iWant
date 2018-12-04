<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Filter;

class FilterPriorityApplier implements FilterApplierInterface
{
    /**
     * @var array $filters
     */
    private $filters = [];

    public function add(FilterInterface $filter, int $priority = 0): void
    {
        $this->filters[] = $this->createFilterMetadata($filter, $priority);
    }
    /**
     * @return bool
     */
    public function hasFilters(): bool
    {
        return !empty($this->filters);
    }
    /**
     * @param array $results
     * @return array
     */
    public function apply(array $results): array
    {
        if (!$this->hasFilters()) {
            return $results;
        }

        $filtersCopy = $this->filters;

        usort($filtersCopy, function(object $a, object $b) {
            return $a->getPriority() <= $b->getPriority();
        });

        foreach ($filtersCopy as $metadata) {
            /** @var FilterInterface $filter */
            $filter = $metadata->getFilter();

            $results = $filter->filter($results);
        }

        return $results;
    }
    /**
     * @param FilterInterface $filter
     * @param int $priority
     * @return object
     */
    private function createFilterMetadata(FilterInterface $filter, int $priority): object
    {
        return new class($filter, $priority) {
            /**
             * @var FilterInterface $filter
             */
            private $filter;
            /**
             * @var int $priority
             */
            private $priority;

            public function __construct(FilterInterface $filter, int $priority)
            {
                $this->filter = $filter;
                $this->priority = $priority;
            }
            /**
             * @return FilterInterface
             */
            public function getFilter(): FilterInterface
            {
                return $this->filter;
            }
            /**
             * @return int
             */
            public function getPriority(): int
            {
                return $this->priority;
            }
        };
    }
}