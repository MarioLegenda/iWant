<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\SearchResponseCache;
use App\Doctrine\Entity\SearchCache;

class SearchResponseCacheImplementation
{
    /**
     * @var SearchResponseCache $searchResponseCache
     */
    private $searchResponseCache;
    /**
     * SearchResponseCacheImplementation constructor.
     * @param SearchResponseCache $searchResponseCache
     */
    public function __construct(
        SearchResponseCache $searchResponseCache
    ) {
        $this->searchResponseCache = $searchResponseCache;
    }

    /**
     * @param string $uniqueName
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function isStored(string $uniqueName): bool
    {
        $searchCache = $this->searchResponseCache->get($uniqueName);

        return $searchCache instanceof SearchCache;
    }
    /**
     * @param string $uniqueName
     * @param string $value
     * @param int $productCount
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $uniqueName,
        string $value,
        int $productCount
    ): bool {
        $this->searchResponseCache->set(
            $uniqueName,
            $value,
            $productCount
        );

        return true;
    }
    /**
     * @param string $key
     * @param string $value
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $key,
        string $value
    ): void {
        $this->searchResponseCache->update($key, $value);
    }
    /**
     * @param string $uniqueName
     * @return SearchCache
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $uniqueName): SearchCache
    {
        /** @var SearchCache $cache */
        $cache = $this->searchResponseCache->get($uniqueName);

        if (!$cache instanceof SearchCache) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                SearchCache::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $cache;
    }
}