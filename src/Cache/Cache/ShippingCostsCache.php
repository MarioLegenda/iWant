<?php

namespace App\Cache\Cache;

use App\Doctrine\Entity\ShippingCostsItem;
use App\Doctrine\Repository\ShippingCostsItemRepository;
use App\Library\Util\Util;
use App\Cache\Exception\CacheException;

class ShippingCostsCache
{
    /**
     * @var ShippingCostsItemRepository $shippingCostItemRepository
     */
    private $shippingCostItemRepository;
    /**
     * @var int $cacheTtl
     */
    private $cacheTtl;
    /**
     * SingleProductItemCache constructor.
     * @param ShippingCostsItemRepository $shippingCostItemRepository
     * @param int $cacheTtl
     */
    public function __construct(
        ShippingCostsItemRepository $shippingCostItemRepository,
        int $cacheTtl
    ) {
        $this->shippingCostItemRepository = $shippingCostItemRepository;
        $this->cacheTtl = $cacheTtl;
    }
    /**
     * @param string $identifier
     * @param null $default
     * @return ShippingCostsItem|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get(
        string $identifier,
        $default = null
    ): ?ShippingCostsItem {
        /** @var ShippingCostsItem $shippingCostsItem */
        $shippingCostsItem = $this->shippingCostItemRepository->findOneBy([
            'identifier' => $identifier
        ]);

        if ($shippingCostsItem instanceof ShippingCostsItem) {
            $expiresAt = $shippingCostsItem->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $currentTimestamp - $expiresAt;

            if ($ttlTimestamp >= 0) {
                $this->deleteObject($shippingCostsItem);

                return null;
            }
        }

        return ($shippingCostsItem instanceof ShippingCostsItem) ?
            $shippingCostsItem :
            null;
    }
    /**
     * @param string $identifier
     * @param string $itemId
     * @param string $response
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $identifier,
        string $itemId,
        string $response
    ) {
        $cache = $this->createShippingCostsItem(
            $identifier,
            $itemId,
            $response,
            Util::toDateTime()->getTimestamp() + $this->cacheTtl
        );

        $this->shippingCostItemRepository->persistAndFlush($cache);

    }
    /**
     * @param string $identifier
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(string $identifier): bool
    {
        /** @var ShippingCostsItem $singleProductItem */
        $shippingCostsItem = $this->shippingCostItemRepository->findOneBy([
            'identifier' => $identifier,
        ]);

        if ($shippingCostsItem instanceof ShippingCostsItem) {
            $this->shippingCostItemRepository->getManager()->remove($singleProductItem);
            $this->shippingCostItemRepository->getManager()->flush();

            return true;
        }

        return false;
    }
    /**
     * @param ShippingCostsItem $shippingCostsItem
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteObject(ShippingCostsItem $shippingCostsItem)
    {
        $this->shippingCostItemRepository->getManager()->remove($shippingCostsItem);
        $this->shippingCostItemRepository->getManager()->flush();

        return true;
    }
    /**
     * @param string $identifier
     * @param string $itemId
     * @param string $response
     * @param int $expiresAt
     * @return ShippingCostsItem
     */
    private function createShippingCostsItem(
        string $identifier,
        string $itemId,
        string $response,
        int $expiresAt
    ): ShippingCostsItem {
        return new ShippingCostsItem(
            $identifier,
            $itemId,
            $response,
            $expiresAt
        );
    }
}