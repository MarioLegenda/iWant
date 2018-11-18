<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\SearchResponseCache;
use App\Doctrine\Entity\SearchCache;
use App\Library\Util\Util;

class SearchResponseCacheImplementation
{
    /**
     * @var SearchResponseCache $searchResponseCache
     */
    private $searchResponseCache;
    /**
     * TodayProductCacheImplementation constructor.
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
     * @param int $page
     * @param string $value
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $uniqueName,
        int $page,
        string $value
    ): bool {
        $this->searchResponseCache->set(
            $uniqueName,
            $page,
            $value
        );

        return true;
    }
    /**
     * @param string $key
     * @param int $page
     * @param string $value
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $key,
        int $page,
        string $value
    ): void {
        $this->searchResponseCache->update($key, $page, $value);
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