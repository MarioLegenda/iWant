<?php

namespace App\Cache\Cache;

use App\Cache\Exception\CacheException;
use App\Doctrine\Repository\ItemTranslationCacheRepository;
use App\Doctrine\Entity\ItemTranslationCache as ItemTranslationCacheEntity;
use App\Library\Util\Util;

class ItemTranslationCache
{
    /**
     * @var ItemTranslationCacheRepository $itemTranslationRepository
     */
    private $itemTranslationRepository;
    /**
     * TodaysProductsCache constructor.
     * @param ItemTranslationCacheRepository $itemTranslationCacheRepository
     */
    public function __construct(
        ItemTranslationCacheRepository $itemTranslationCacheRepository
    ) {
        $this->itemTranslationRepository = $itemTranslationCacheRepository;
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @param null $default
     * @return ItemTranslationCacheEntity|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get(
        string $uniqueName,
        string $itemId,
        $default = null
    ): ?ItemTranslationCacheEntity {
        /** @var ItemTranslationCacheEntity $searchCache */
        $itemTranslationCache = $this->itemTranslationRepository->findOneBy([
            'uniqueName' => $uniqueName,
            'itemId' => $itemId,
        ]);

        if ($itemTranslationCache instanceof ItemTranslationCacheEntity) {
            $expiresAt = $itemTranslationCache->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $currentTimestamp - $expiresAt;

            if ($ttlTimestamp >= 0) {
                $this->deleteObject($itemTranslationCache);
            }
        }

        return ($itemTranslationCache instanceof ItemTranslationCacheEntity) ?
            $searchCache :
            null;
    }
    /**
     * @param ItemTranslationCacheEntity $itemTranslationCache
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteObject(
        ItemTranslationCacheEntity $itemTranslationCache
    ) {
        $this->itemTranslationRepository->getManager()->remove($itemTranslationCache);
        $this->itemTranslationRepository->getManager()->flush();

        return true;
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @return bool
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(
        string $uniqueName,
        string $itemId
    ): bool {
        $itemTranslationCache = $this->itemTranslationRepository->findOneBy([
            'uniqueName' => $uniqueName,
            'itemId' => $itemId,
        ]);

        if (!$itemTranslationCache instanceof ItemTranslationCacheEntity) {
            $message = sprintf(
                '%s with unique name %s and itemId %s could not be found',
                ItemTranslationCacheEntity::class,
                $uniqueName,
                $itemId
            );

            throw new CacheException($message);
        }

        $this->itemTranslationRepository->getManager()->remove($itemTranslationCache);
        $this->itemTranslationRepository->getManager()->flush();

        return true;
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @param string $translations
     * @param int|null $ttl
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $uniqueName,
        string $itemId,
        string $translations,
        int $ttl = null
    ) {
        if (empty($ttl)) {
            $message = sprintf(
                'TTL has to be implemented in %s::set()',
                get_class($this)
            );

            throw new CacheException($message);
        }

        if (!is_int($ttl)) {
            $message = sprintf(
                'TTL has to be an timestamp integer'
            );

            throw new CacheException($message);
        }

        $cache = $this->createItemTranslationCache(
            $uniqueName,
            $itemId,
            $translations
        );

        $this->itemTranslationRepository->persistAndFlush($cache);
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @param string $translations
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $uniqueName,
        string $itemId,
        string $translations
    ): void {
        $itemTranslationCache = $this->itemTranslationRepository->findOneBy([
            'uniqueName' => $uniqueName,
            'itemId' => $itemId,
        ]);

        if (!$itemTranslationCache instanceof ItemTranslationCacheEntity) {
            $message = sprintf(
                '%s with unique name %s and itemId %s could not be found',
                ItemTranslationCacheEntity::class,
                $uniqueName,
                $itemId
            );

            throw new CacheException($message);
        }

        $itemTranslationCache->setTranslations($translations);

        $this->itemTranslationRepository->getManager()->persist($itemTranslationCache);
        $this->itemTranslationRepository->getManager()->flush();
    }
    /**
     * @param string $uniqueName
     * @param string $itemId
     * @param string $translations
     * @return ItemTranslationCacheEntity
     */
    public function createItemTranslationCache(
        string $uniqueName,
        string $itemId,
        string $translations
    ): ItemTranslationCacheEntity {
        return new ItemTranslationCacheEntity(
            $uniqueName,
            $itemId,
            $translations
        );
    }
}