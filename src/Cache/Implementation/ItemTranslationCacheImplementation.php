<?php

namespace App\Cache\Implementation;

use App\Doctrine\Entity\ItemTranslationCache as ItemTranslationCacheEntity;
use App\Cache\Cache\ItemTranslationCache;
use App\Library\Util\Util;

class ItemTranslationCacheImplementation
{
    /**
     * @var ItemTranslationCache $itemTranslationCache
     */
    private $itemTranslationCache;
    /**
     * TodayProductCacheImplementation constructor.
     * @param ItemTranslationCache $itemTranslationCache
     */
    public function __construct(
        ItemTranslationCache $itemTranslationCache
    ) {
        $this->itemTranslationCache = $itemTranslationCache;
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function isStored(
        string $uniqueName,
        string $itemId
    ): bool {
        /** @var ItemTranslationCache $itemTranslationCache */
        $itemTranslationCache = $this->itemTranslationCache->get(
            $uniqueName,
            $itemId
        );

        return $itemTranslationCache instanceof ItemTranslationCache;
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @param array $translations
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $uniqueName,
        string $itemId,
        array $translations
    ): bool {

        $this->itemTranslationCache->set(
            $uniqueName,
            $itemId,
            json_encode($translations),
            $this->calculateTTL()
        );

        return true;
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $uniqueName, string $itemId): array
    {
        /** @var ItemTranslationCacheEntity $itemTranslationCache */
        $itemTranslationCache = $this->itemTranslationCache->get(
            $uniqueName,
            $itemId
        );

        if (!$itemTranslationCache instanceof ItemTranslationCacheEntity) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                ItemTranslationCache::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return json_decode($itemTranslationCache->getTranslations(), true);
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @param array $translations
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $uniqueName,
        string $itemId,
        array $translations
    ) {
        $this->itemTranslationCache->update(
            $uniqueName,
            $itemId,
            json_encode($translations)
        );
    }
    /**
     * @return int
     */
    private function calculateTTL(): int
    {
        $currentTimestamp = Util::toDateTime()->getTimestamp();
        $days5 = 60 * 60 * 24 * 5;

        return $currentTimestamp + $days5;
    }
}