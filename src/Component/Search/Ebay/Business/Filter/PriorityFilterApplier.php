<?php

namespace App\Component\Search\Ebay\Business\Filter;

use App\Library\Util\Util;

class PriorityFilterApplier implements FilterApplierInterface
{
    /**
     * @var array $filters
     */
    private $filters = [];
    /**
     * @param array $entries
     * @return array
     */
    public function apply(array $entries): array
    {
        usort($this->filters, function(object $a, object $b) {
            return $a->getPriority() >= $b->getPriority();
        });

        $filtersGen = Util::createGenerator($this->filters);

        $filteredEntries = [];
        foreach ($filtersGen as $entry) {
            /** @var FilterInterface $filter */
            $filter = $entry['item']->getFilter();

            if (!empty($filteredEntries)) {
                $filteredEntries = $filter->filter($filteredEntries);

                continue;
            }

            $filteredEntries = $filter->filter($entries);
        }

        return $filteredEntries;
    }
    /**
     * @return bool
     */
    public function hasFilters(): bool
    {
        return !empty($this->filters);
    }
    /**
     * @param FilterInterface $filter
     * @param int $priority
     */
    public function add(FilterInterface $filter, int $priority = 0): void
    {
        $this->filters[] = $this->createMetadata($filter, $priority);
    }
    /**
     * @param FilterInterface $filter
     * @param int $priority
     * @return object
     */
    private function createMetadata(FilterInterface $filter, int $priority = 0): object
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
            /**
             *  constructor.
             * @param FilterInterface $filter
             * @param int $priority
             */
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