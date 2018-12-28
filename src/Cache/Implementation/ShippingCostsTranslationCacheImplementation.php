<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\ShippingCostsTranslationCache;
use App\Doctrine\Entity\ShippingCostsTranslationCache as ShippingCostsTranslationCacheEntity;

class ShippingCostsTranslationCacheImplementation
{
    /**
     * @var ShippingCostsTranslationCache $shippingCostsTranslationCache
     */
    private $shippingCostsTranslationCache;
    /**
     * ShippingCostsTranslationCacheImplementation constructor.
     * @param ShippingCostsTranslationCache $shippingCostsTranslationCache
     */
    public function __construct(
        ShippingCostsTranslationCache $shippingCostsTranslationCache
    ) {
        $this->shippingCostsTranslationCache = $shippingCostsTranslationCache;
    }
    /**
     * @param string $itemId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function isStored(
        string $itemId
    ): bool {
        /** @var ShippingCostsTranslationCache $shippingCostsTranslationCache */
        $shippingCostsTranslationCache = $this->shippingCostsTranslationCache->get(
            $itemId
        );

        return $shippingCostsTranslationCache instanceof ShippingCostsTranslationCache;
    }
    /**
     * @param string $itemId
     * @param array|iterable $translations
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $itemId,
        array $translations
    ): bool {
        $this->shippingCostsTranslationCache->set(
            $itemId,
            jsonEncodeWithFix($translations)
        );

        return true;
    }
    /**
     * @param string $itemId
     * @return ShippingCostsTranslationCacheEntity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $itemId): ShippingCostsTranslationCacheEntity
    {
        /** @var ShippingCostsTranslationCacheEntity $shippingCostsTranslationCache */
        $shippingCostsTranslationCache = $this->shippingCostsTranslationCache->get(
            $itemId
        );

        if (!$shippingCostsTranslationCache instanceof ShippingCostsTranslationCacheEntity) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                ShippingCostsTranslationCacheEntity::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $shippingCostsTranslationCache;
    }
    /**
     * @param string $itemId
     * @param array|iterable $translations
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        string $itemId,
        array $translations
    ) {
        $this->shippingCostsTranslationCache->update(
            $itemId,
            jsonEncodeWithFix($translations)
        );
    }
}