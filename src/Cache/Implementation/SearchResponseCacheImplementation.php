<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\SearchResponseCache;
use App\Doctrine\Entity\SearchCache;
use App\Doctrine\Entity\ToggleCache;
use App\Doctrine\Repository\ToggleCacheRepository;
use App\Library\Util\Util;

class SearchResponseCacheImplementation
{
    /**
     * @var SearchResponseCache $searchResponseCache
     */
    private $searchResponseCache;
    /**
     * @var SearchResponseCache $searchResponseCache
     */
    private $toggleCacheRepository;
    /**
     * TodayProductCacheImplementation constructor.
     * @param SearchResponseCache $searchResponseCache
     * @param ToggleCacheRepository $toggleCacheRepository
     */
    public function __construct(
        SearchResponseCache $searchResponseCache,
        ToggleCacheRepository $toggleCacheRepository
    ) {
        $this->searchResponseCache = $searchResponseCache;
        $this->toggleCacheRepository = $toggleCacheRepository;
    }
    /**
     * @param string $uniqueName
     * @return bool
     */
    public function isStored(string $uniqueName): bool
    {
        /** @var ToggleCache $toggleCache */
        $toggleCache = $this->toggleCacheRepository->findAll()[0];

        if ($toggleCache->getTodaysKeywordsCache() === false) {
            return false;
        }

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
        /** @var ToggleCache $toggleCache */
        $toggleCache = $this->toggleCacheRepository->findAll()[0];

        if ($toggleCache->getTodaysKeywordsCache() === false) {
            return false;
        }

        $this->searchResponseCache->set(
            $uniqueName,
            $page,
            $value,
            $this->calculateTTL()
        );

        return true;
    }
    /**
     * @param string $uniqueName
     * @return string
     */
    public function getStored(string $uniqueName): string
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

        return $cache->getProductsResponse();
    }
    /**
     * @return int
     */
    private function calculateTTL(): int
    {
        $currentTimestamp = Util::toDateTime()->getTimestamp();
        $hours24 = 60 * 60 * 24;

        return $currentTimestamp + $hours24;
    }
}