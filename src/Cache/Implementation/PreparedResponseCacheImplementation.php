<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\PreparedResponseCache;
use App\Cache\Cache\SearchResponseCache;
use App\Doctrine\Entity\ToggleCache;
use App\Library\Util\Util;
use App\Doctrine\Repository\ToggleCacheRepository;
use App\Doctrine\Entity\PreparedResponseCache as PreparedResponseCacheEntity;

class PreparedResponseCacheImplementation
{
    /**
     * @var PreparedResponseCache $preparedResponseCache
     */
    private $preparedResponseCache;
    /**
     * @var ToggleCacheRepository $toggleCacheRepository
     */
    private $toggleCacheRepository;
    /**
     * TodayProductCacheImplementation constructor.
     * @param PreparedResponseCache $preparedResponseCache
     * @param ToggleCacheRepository $toggleCacheRepository
     */
    public function __construct(
        PreparedResponseCache $preparedResponseCache,
        ToggleCacheRepository $toggleCacheRepository
    ) {
        $this->preparedResponseCache = $preparedResponseCache;
        $this->toggleCacheRepository = $toggleCacheRepository;
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
        /** @var ToggleCache $toggleCache */
        $toggleCache = $this->toggleCacheRepository->findAll()[0];

        if ($toggleCache->getTodaysKeywordsCache() === false) {
            return false;
        }

        /** @var PreparedResponseCacheEntity $preparedResponseCacheEntity */
        $preparedResponseCacheEntity = $this->preparedResponseCache->get($uniqueName);

        return $preparedResponseCacheEntity instanceof PreparedResponseCacheEntity;
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
        string $value
    ): bool {
        /** @var ToggleCache $toggleCache */
        $toggleCache = $this->toggleCacheRepository->findAll()[0];

        if ($toggleCache->getTodaysKeywordsCache() === false) {
            return false;
        }

        $this->preparedResponseCache->set(
            $uniqueName,
            $value,
            $this->calculateTTL()
        );

        return true;
    }
    /**
     * @param string $uniqueName
     * @return string
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $uniqueName): string
    {
        /** @var PreparedResponseCacheEntity $cache */
        $cache = $this->preparedResponseCache->get($uniqueName);

        if (!$cache instanceof PreparedResponseCacheEntity) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                PreparedResponseCacheEntity::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $cache->getResponse();
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