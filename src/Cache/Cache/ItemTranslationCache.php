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
     * @var int $cacheTtl
     */
    private $cacheTtl;
    /**
     * ItemTranslationCache constructor.
     * @param ItemTranslationCacheRepository $itemTranslationCacheRepository
     * @param int $cacheTtl
     */
    public function __construct(
        ItemTranslationCacheRepository $itemTranslationCacheRepository,
        int $cacheTtl
    ) {
        $this->itemTranslationRepository = $itemTranslationCacheRepository;
        $this->cacheTtl = $cacheTtl;
    }
    /**
     * @param string $itemId
     * @param null $default
     * @return ItemTranslationCacheEntity|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get(
        string $itemId,
        $default = null
    ): ?ItemTranslationCacheEntity {
        /** @var ItemTranslationCacheEntity $itemTranslationCache */
        $itemTranslationCache = $this->itemTranslationRepository->findOneBy([
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
            $itemTranslationCache :
            null;
    }
    /**
     * @param string $itemId
     * @param null $default
     * @return ItemTranslationCacheEntity|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getByItemId(
        string $itemId,
        $default = null
    ): ?ItemTranslationCacheEntity {
        /** @var ItemTranslationCacheEntity $itemTranslationCache */
        $itemTranslationCache = $this->itemTranslationRepository->findOneBy([
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
            $itemTranslationCache :
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
     * @param string $itemId
     * @param string $translations
     * @param int|null $ttl
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $itemId,
        string $translations
    ) {
        $cache = $this->createItemTranslationCache(
            $itemId,
            $translations,
            Util::toDateTime()->getTimestamp() + $this->cacheTtl
        );

        $this->itemTranslationRepository->persistAndFlush($cache);
    }
    /**
     * @param string $itemId
     * @param string $translations
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $itemId,
        string $translations
    ): void {
        $itemTranslationCache = $this->itemTranslationRepository->findOneBy([
            'itemId' => $itemId,
        ]);

        if (!$itemTranslationCache instanceof ItemTranslationCacheEntity) {
            $message = sprintf(
                '%s with itemId %s could not be found',
                ItemTranslationCacheEntity::class,
                $itemId
            );

            throw new CacheException($message);
        }

        $itemTranslationCache->setTranslations($translations);

        $this->itemTranslationRepository->getManager()->persist($itemTranslationCache);
        $this->itemTranslationRepository->getManager()->flush();
    }
    /**
     * @param string $itemId
     * @param string $translations
     * @param int $ttl
     * @return ItemTranslationCacheEntity
     */
    public function createItemTranslationCache(
        string $itemId,
        string $translations,
        int $ttl
    ): ItemTranslationCacheEntity {
        return new ItemTranslationCacheEntity(
            $itemId,
            $translations,
            $ttl
        );
    }
}