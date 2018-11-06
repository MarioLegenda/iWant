<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\SingleProductItemCache;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class SingleProductItemCacheImplementation
{
    /**
     * @var SingleProductItemCache $singleProductItemCache
     */
    private $singleProductItemCache;
    /**
     * SingleProductItemCacheImplementation constructor.
     * @param SingleProductItemCache $singleProductItemCache
     */
    public function __construct(
        SingleProductItemCache $singleProductItemCache
    ) {
        $this->singleProductItemCache = $singleProductItemCache;
    }
    /**
     * @param string $itemId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function isStored(string $itemId): bool
    {
        /** @var SingleProductItem $singleProductItem */
        $singleProductItem = $this->singleProductItemCache->get($itemId);

        return $singleProductItem instanceof SingleProductItem;
    }
    /**
     * @param string $itemId
     * @param string $response
     * @param MarketplaceType $marketplaceType
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $itemId,
        string $response,
        MarketplaceType $marketplaceType
    ): bool {
        $this->singleProductItemCache->set(
            $itemId,
            $response,
            $this->calculateTTL(),
            $marketplaceType
        );

        return true;
    }
    /**
     * @param string $itemId
     * @return SingleProductItem
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $itemId): SingleProductItem
    {
        /** @var SingleProductItem $cache */
        $cache = $this->singleProductItemCache->get($itemId);

        if (!$cache instanceof SingleProductItem) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                SingleProductItem::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $cache;
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