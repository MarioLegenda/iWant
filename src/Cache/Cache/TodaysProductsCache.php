<?php

namespace App\Cache\Cache;

use App\Cache\CacheInterface;
use App\Doctrine\Repository\TodaysProductsCacheRepository;
use App\Library\Util\Util;
use App\Doctrine\Entity\TodaysProductsCache as TodaysProductCacheEntity;

class TodaysProductsCache implements CacheInterface
{
    /**
     * @var TodaysProductsCacheRepository $todayProductsCacheRepository
     */
    private $todayProductsCacheRepository;
    /**
     * TodaysProductsCache constructor.
     * @param TodaysProductsCacheRepository $todaysProductsCacheRepository
     */
    public function __construct(
        TodaysProductsCacheRepository $todaysProductsCacheRepository
    ) {
        $this->todayProductsCacheRepository = $todaysProductsCacheRepository;
    }
    /**
     * @param string $key
     * @param null $default
     * @return TodaysProductCacheEntity|null
     */
    public function get($key, $default = null): ?TodaysProductCacheEntity
    {
        $storedAt = Util::toDateTime($key, Util::getSimpleDateApplicationFormat());

        /** @var TodaysProductCacheEntity $todayProductsCache */
        $todayProductsCache = $this->todayProductsCacheRepository->findOneBy([
            'storedAt' => $storedAt,
        ]);

        return ($todayProductsCache instanceof TodaysProductCacheEntity) ?
            $todayProductsCache :
            null;
    }
    /**
     * @param string $key
     * @param mixed $value
     * @param null $ttl
     * @return bool|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set($key, $value, $ttl = null)
    {
        if (!$ttl instanceof \DateTime) {
            $message = sprintf(
                'ttl has to be a \DateTime object'
            );

            throw new \RuntimeException($message);
        }

        if (!$key instanceof \DateTime) {
            $message = sprintf(
                'key has to be a \DateTime object'
            );

            throw new \RuntimeException($message);
        }

        $cache = $this->createTodaysProductsCache(
            $value,
            $key,
            $ttl
        );

        $this->todayProductsCacheRepository->persistAndFlush($cache);
    }
    /**
     * @param iterable $values
     * @param null $ttl
     * @return bool|void
     */
    public function setMultiple($values, $ttl = null)
    {
        $message = sprintf(
            'Method %s::has() is not implemented',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param string $key
     * @return bool|void
     */
    public function has($key)
    {
        $message = sprintf(
            'Method %s::has() is not implemented',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param iterable $keys
     * @param null $default
     * @return iterable|void
     */
    public function getMultiple($keys, $default = null)
    {
        $message = sprintf(
            'Method %s::getMultiple() is not implemented',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param iterable $keys
     * @return bool|void
     */
    public function deleteMultiple($keys)
    {
        $message = sprintf(
            'Method %s::deleteMultiple() is not implemented',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param string $key
     * @return bool|void
     */
    public function delete($key)
    {
        $message = sprintf(
            'Method %s::delete() is not implemented',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return bool|void
     */
    public function clear()
    {
        $message = sprintf(
            'Method %s::clear() is not implemented',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param string $productResponse
     * @param \DateTime $storedAt
     * @param \DateTime $ttl
     * @return TodaysProductCacheEntity
     */
    private function createTodaysProductsCache(
        string $productResponse,
        \DateTime $storedAt,
        \DateTime $ttl
    ) {
        return new TodaysProductCacheEntity(
            $productResponse,
            $storedAt,
            $ttl
        );
    }
}