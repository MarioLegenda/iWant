<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\TodaysProductsCache;
use App\Library\Util\Util;
use App\Doctrine\Entity\TodaysProductsCache as TodaysProductsCacheEntity;

class TodayProductCacheImplementation
{
    /**
     * @var TodaysProductsCache $todaysProductsCache
     */
    private $todaysProductsCache;
    /**
     * TodayProductCacheImplementation constructor.
     * @param TodaysProductsCache $todaysProductsCache
     */
    public function __construct(
        TodaysProductsCache $todaysProductsCache
    ) {
        $this->todaysProductsCache = $todaysProductsCache;
    }
    /**
     * @param \DateTime $storedAt
     * @return bool
     */
    public function isStored(\DateTime $storedAt): bool
    {
        $todayProductCache = $this->todaysProductsCache->get(Util::formatFromDate($storedAt));

        return $todayProductCache instanceof TodaysProductsCacheEntity;
    }
    /**
     * @param \DateTime $storedAt
     * @param string $value
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        \DateTime $storedAt,
        string $value
    ): bool {
        $this->todaysProductsCache->set(
            $this->resolveStoredAt($storedAt),
            $value,
            $this->calculateTTL($storedAt)
        );

        return true;
    }
    /**
     * @param \DateTime $storedAt
     * @return string
     */
    public function getStored(\DateTime $storedAt): string
    {
        /** @var TodaysProductsCacheEntity $cache */
        $cache = $this->todaysProductsCache->get(Util::formatFromDate($storedAt));

        if (!$cache instanceof TodaysProductsCacheEntity) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                TodaysProductsCacheEntity::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $cache->getProductsResponse();
    }
    /**
     * @param \DateTime $storedAt
     * @return \DateTime
     */
    public function resolveStoredAt(\DateTime $storedAt): \DateTime
    {
        $newStoredAt = new \DateTime($storedAt->format(Util::getSimpleDateApplicationFormat()));
        $newStoredAt->setTime(0, 0, 0);

        return $newStoredAt;
    }
    /**
     * @param \DateTime $storedAt
     * @return \DateTime
     */
    private function calculateTTL(\DateTime $storedAt): \DateTime
    {
        $newStoredAt = new \DateTime($storedAt->format(Util::getSimpleDateApplicationFormat()));

        $newStoredAt->modify('+1 day');

        return new \DateTime($newStoredAt->format(Util::getSimpleDateApplicationFormat()));
    }
}