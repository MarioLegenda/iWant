<?php

namespace App\Component\Search\Ebay\Business\Filter;

use App\Doctrine\Entity\SearchQueryFilter;
use App\Doctrine\Repository\SearchQueryFilterRepository;
use App\Library\Util\Util;

class SearchQueryRegexFilter implements FilterInterface
{
    /**
     * @var SearchQueryFilterRepository $searchQueryFilterRepository
     */
    private $searchQueryFilterRepository;
    /**
     * SearchQueryRegexFilter constructor.
     * @param SearchQueryFilterRepository $searchQueryFilterRepository
     */
    public function __construct(
        SearchQueryFilterRepository $searchQueryFilterRepository
    ) {
        $this->searchQueryFilterRepository = $searchQueryFilterRepository;
    }
    /**
     * @param array $entries
     * @return array
     */
    public function filter(array $entries): array
    {
        $entriesGen = Util::createGenerator($entries);
        $searchQueryFilters = $this->resolveSearchQueryFilters();
        $queryFilterProcessor = $this->createQueryFilterProcessor($searchQueryFilters);

        $filtered = [];
        foreach ($entriesGen as $entry) {
            $item = $entry['item'];

            $title = $item['title']['original'];

            if ($queryFilterProcessor->hasReference($title)) {
                $values = $queryFilterProcessor->getFoundValues($title);

                $found = false;
                foreach ($values as $marker) {
                    if (preg_match(sprintf('#%s#i', strtolower($marker)), strtolower($title)) === 1) {
                        $found = true;

                        break;
                    }
                }

                if ($found) {
                    continue;
                }
            }

            $filtered[] = $item;
        }

        return $filtered;
    }
    /**
     * @return array
     */
    private function resolveSearchQueryFilters(): array
    {
        $searchFilterQueries = $this->searchQueryFilterRepository->findAll();

        if (empty($searchFilterQueries)) {
            $message = sprintf(
                'There are no search query filters. Populate search query filters with app:batch_add_search_query_filters'
            );

            throw new \RuntimeException($message);
        }

        $searchFilterQueriesGen = Util::createGenerator($searchFilterQueries);

        $searchQueryFilters = [];
        /** @var SearchQueryFilter $searchFilterQuery */
        foreach ($searchFilterQueriesGen as $entry) {
            /** @var SearchQueryFilter $item */
            $item = $entry['item'];

            $searchQueryFilters[] = $item->toArray();
        }

        return $searchQueryFilters;
    }
    /**
     * @param array $searchQueryFilters
     * @return object
     */
    private function createQueryFilterProcessor(array $searchQueryFilters): object
    {
        return new class($searchQueryFilters) {
            /**
             * @var array $searchQueryFilters
             */
            private $searchQueryFilters;
            /**
             * @var array $referencesOnly
             */
            private $referencesOnly;
            /**
             *  constructor.
             * @param array|SearchQueryFilter[] $searchQueryFilters
             */
            public function __construct(array $searchQueryFilters)
            {
                $this->searchQueryFilters = $searchQueryFilters;
                $this->referencesOnly = apply_on_iterable($searchQueryFilters, function(array $filters) {
                    return $filters['reference'];
                });
            }
            /**
             * @param string $query
             * @return bool
             */
            public function hasReference(string $query): bool
            {
                $referencesGen = Util::createGenerator($this->referencesOnly);

                foreach ($referencesGen as $entry) {
                    $item = $entry['item'];

                    if (preg_match(sprintf('#%s#i', $item), $query) === 1) {
                        return true;
                    }
                }

                return false;
            }
            /**
             * @param string $query
             * @return array
             */
            public function getFoundValues(string $query): ?array
            {
                $referencesGen = Util::createGenerator($this->referencesOnly);

                $values = [];
                foreach ($referencesGen as $entry) {
                    $item = $entry['item'];

                    if (preg_match(sprintf('#%s#i', $item), $query) === 1) {
                        $values = array_replace_recursive($values, $this->findByReference($item));
                    }
                }

                if (empty($values)) {
                    $message = sprintf(
                        '%s should always return a non empty array. Do your checks before calling this method',
                        __FUNCTION__
                    );

                    throw new \RuntimeException($message);
                }

                return $values;
            }
            /**
             * @param string $reference
             * @return array|null
             */
            public function findByReference(string $reference): array
            {
                foreach ($this->searchQueryFilters as $searchQueryFilter) {
                    if ($reference === $searchQueryFilter['reference']) {
                        return $searchQueryFilter['values'];
                    }
                }

                $message = sprintf(
                    'Method %s of the query filter processor anonymous class should always return a value. Do your checks before calling this method',
                    __FUNCTION__
                );

                throw new \RuntimeException($message);
            }
        };
    }
}