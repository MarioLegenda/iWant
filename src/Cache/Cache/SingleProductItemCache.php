<?php

namespace App\Cache\Cache;

use App\Cache\Exception\CacheException;
use App\Doctrine\Entity\SingleProductItem;
use App\Doctrine\Repository\SingleProductItemRepository;
use App\Library\MarketplaceType;
use App\Library\Util\Util;

class SingleProductItemCache
{
    /**
     * @var SingleProductItemRepository $singleProductItemRepository
     */
    private $singleProductItemRepository;
    /**
     * SingleProductItemCache constructor.
     * @param SingleProductItemRepository $singleProductItemRepository
     */
    public function __construct(
        SingleProductItemRepository $singleProductItemRepository
    ) {
        $this->singleProductItemRepository = $singleProductItemRepository;
    }
    /**
     * @param string $itemId
     * @param null $default
     * @return SingleProductItem|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get(
        string $itemId,
        $default = null
    ): ?SingleProductItem {
        /** @var SingleProductItem $singleProductItem */
        $singleProductItem = $this->singleProductItemRepository->findOneBy([
            'itemId' => $itemId
        ]);

        if ($singleProductItem instanceof SingleProductItem) {
            $expiresAt = $singleProductItem->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $currentTimestamp - $expiresAt;

            if ($ttlTimestamp >= 0) {
                $this->delete($singleProductItem);
            }
        }

        return ($singleProductItem instanceof SingleProductItem) ?
            $singleProductItem :
            null;
    }
    /**
     * @param string $itemId
     * @param string $response
     * @param int $ttl
     * @param MarketplaceType $marketplaceType
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $itemId,
        string $response,
        int $ttl,
        MarketplaceType $marketplaceType
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

        $cache = $this->createSingleProductItem(
            $itemId,
            $response,
            $ttl,
            $marketplaceType
        );

        $this->singleProductItemRepository->persistAndFlush($cache);

    }
    /**
     * @param string $itemId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(string $itemId): bool
    {
        /** @var SingleProductItem $singleProductItem */
        $singleProductItem = $this->singleProductItemRepository->findOneBy([
            'itemId' => $itemId,
        ]);

        if ($singleProductItem instanceof SingleProductItem) {
            $this->singleProductItemRepository->getManager()->remove($singleProductItem);
            $this->singleProductItemRepository->getManager()->flush();

            return true;
        }

        return false;
    }
    /**
     * @param string $itemId
     * @param string $response
     * @param int $expiresAt
     * @param MarketplaceType $marketplaceType
     * @return SingleProductItem
     */
    private function createSingleProductItem(
        string $itemId,
        string $response,
        int $expiresAt,
        MarketplaceType $marketplaceType
    ): SingleProductItem {
        return new SingleProductItem(
            $itemId,
            $response,
            $marketplaceType,
            $expiresAt
        );
    }
}