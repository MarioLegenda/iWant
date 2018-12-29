<?php

namespace App\Cache\Implementation;

use App\Cache\Cache\ShippingCostsCache;
use App\Doctrine\Entity\ShippingCostsItem;

class ShippingCostsCacheImplementation
{
    /**
     * @var ShippingCostsCache $shippingCostsCache
     */
    private $shippingCostsCache;
    /**
     * SingleProductItemCacheImplementation constructor.
     * @param ShippingCostsCache $shippingCostsCache
     */
    public function __construct(
        ShippingCostsCache $shippingCostsCache
    ) {
        $this->shippingCostsCache = $shippingCostsCache;
    }
    /**
     * @param string $identifier
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function isStored(string $identifier): bool
    {
        /** @var ShippingCostsItem $shippingCostsItem */
        $shippingCostsItem = $this->shippingCostsCache->get($identifier);

        return $shippingCostsItem instanceof ShippingCostsItem;
    }
    /**
     * @param string $identifier
     * @param string $itemId
     * @param string $response
     * @return bool
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(
        string $identifier,
        string $itemId,
        string $response
    ): bool {
        $this->shippingCostsCache->set(
            $identifier,
            $itemId,
            $response
        );

        return true;
    }
    /**
     * @param string $itemId
     * @return ShippingCostsItem
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getStored(string $itemId): ShippingCostsItem
    {
        /** @var ShippingCostsItem $shippingCostsItem */
        $shippingCostsItem = $this->shippingCostsCache->get($itemId);

        if (!$shippingCostsItem instanceof ShippingCostsItem) {
            $message = sprintf(
                'Invalid usage of %s::getStored(). This method should always return a %s. Check if a resource is stored with %s::isStored()',
                get_class($this),
                ShippingCostsItem::class,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $shippingCostsItem;
    }
}