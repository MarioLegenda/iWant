<?php

namespace App\Cache\Cache;

use App\Doctrine\Entity\ShippingCostsTranslationCache as ShippingCostsTranslationCacheEntity;
use App\Doctrine\Repository\ShippingCostsTranslationCacheRepository;
use App\Library\Util\Util;
use App\Cache\Exception\CacheException;

class ShippingCostsTranslationCache
{
    /**
     * @var ShippingCostsTranslationCacheRepository $itemTranslationRepository
     */
    private $shippingCostsTranslationCacheRepository;
    /**
     * @var int $cacheTtl
     */
    private $cacheTtl;
    /**
     * ItemTranslationCache constructor.
     * @param ShippingCostsTranslationCacheRepository $shippingCostsTranslationCacheRepository
     * @param int $cacheTtl
     */
    public function __construct(
        ShippingCostsTranslationCacheRepository $shippingCostsTranslationCacheRepository,
        int $cacheTtl
    ) {
        $this->shippingCostsTranslationCacheRepository = $shippingCostsTranslationCacheRepository;
        $this->cacheTtl = $cacheTtl;
    }
    /**
     * @param string $itemId
     * @param null $default
     * @return ShippingCostsTranslationCacheEntity|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get(
        string $itemId,
        $default = null
    ): ?ShippingCostsTranslationCacheEntity {
        /** @var ShippingCostsTranslationCacheEntity $itemTranslationCache */
        $shippingCostsTranslationEntity = $this->shippingCostsTranslationCacheRepository->findOneBy([
            'itemId' => $itemId,
        ]);

        if ($shippingCostsTranslationEntity instanceof ShippingCostsTranslationCacheEntity) {
            $expiresAt = $shippingCostsTranslationEntity->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $currentTimestamp - $expiresAt;

            if ($ttlTimestamp >= 0) {
                $this->deleteObject($shippingCostsTranslationEntity);

                return null;
            }
        }

        return ($shippingCostsTranslationEntity instanceof ShippingCostsTranslationCacheEntity) ?
            $shippingCostsTranslationEntity :
            null;
    }
    /**
     * @param string $itemId
     * @param null $default
     * @return ShippingCostsTranslationCacheEntity|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getByItemId(
        string $itemId,
        $default = null
    ): ?ShippingCostsTranslationCacheEntity {
        /** @var ShippingCostsTranslationCacheEntity $itemTranslationCache */
        $shippingCostsTranslationCacheEntity = $this->shippingCostsTranslationCacheRepository->findOneBy([
            'itemId' => $itemId,
        ]);

        if ($shippingCostsTranslationCacheEntity instanceof ShippingCostsTranslationCacheEntity) {
            $expiresAt = $shippingCostsTranslationCacheEntity->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $currentTimestamp - $expiresAt;

            if ($ttlTimestamp >= 0) {
                $this->deleteObject($shippingCostsTranslationCacheEntity);
            }
        }

        return ($shippingCostsTranslationCacheEntity instanceof ShippingCostsTranslationCacheEntity) ?
            $shippingCostsTranslationCacheEntity :
            null;
    }
    /**
     * @param ShippingCostsTranslationCacheEntity $shippingCostsTranslationCacheEntity
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteObject(
        ShippingCostsTranslationCacheEntity $shippingCostsTranslationCacheEntity
    ) {
        $this->shippingCostsTranslationCacheRepository->getManager()->remove($shippingCostsTranslationCacheEntity);
        $this->shippingCostsTranslationCacheRepository->getManager()->flush();

        return true;
    }
    /**
     * @param string $itemId
     * @param string $translations
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $itemId,
        string $translations
    ) {
        $cache = $this->createShippingCostsTranslationCache(
            $itemId,
            $translations,
            Util::toDateTime()->getTimestamp() + $this->cacheTtl
        );

        $this->shippingCostsTranslationCacheRepository->persistAndFlush($cache);
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
        $shippingCostsTranslationCacheEntity = $this->shippingCostsTranslationCacheRepository->findOneBy([
            'itemId' => $itemId,
        ]);

        if (!$shippingCostsTranslationCacheEntity instanceof ShippingCostsTranslationCacheEntity) {
            $message = sprintf(
                '%s with itemId %s could not be found',
                ShippingCostsTranslationCacheEntity::class,
                $itemId
            );

            throw new CacheException($message);
        }

        $shippingCostsTranslationCacheEntity->setTranslations($translations);

        $this->shippingCostsTranslationCacheRepository->getManager()->persist($shippingCostsTranslationCacheEntity);
        $this->shippingCostsTranslationCacheRepository->getManager()->flush();
    }
    /**
     * @param string $itemId
     * @param string $translations
     * @param int $ttl
     * @return ShippingCostsTranslationCacheEntity
     */
    public function createShippingCostsTranslationCache(
        string $itemId,
        string $translations,
        int $ttl
    ): ShippingCostsTranslationCacheEntity {
        return new ShippingCostsTranslationCacheEntity(
            $itemId,
            $translations,
            $ttl
        );
    }
}