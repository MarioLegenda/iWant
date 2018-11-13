<?php

namespace App\Cache\Cache;

use App\Cache\Exception\CacheException;
use App\Doctrine\Entity\PreparedResponseCache as PreparedResponseCacheEntity;
use App\Doctrine\Repository\PreparedResponseCacheRepository;
use App\Library\Util\Util;

class PreparedResponseCache
{
    /**
     * @var int $cacheTtl
     */
    private $cacheTtl;
    /**
     * @var PreparedResponseCacheRepository $preparedResponseCacheRepository
     */
    private $preparedResponseCacheRepository;
    /**
     * PreparedResponseCache constructor.
     * @param PreparedResponseCacheRepository $preparedResponseCacheRepository
     * @param int $cacheTtl
     */
    public function __construct(
        PreparedResponseCacheRepository $preparedResponseCacheRepository,
        int $cacheTtl
    ) {
        $this->preparedResponseCacheRepository = $preparedResponseCacheRepository;
        $this->cacheTtl;
    }
    /**
     * @param $key
     * @param null $default
     * @return PreparedResponseCacheEntity|null
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get($key, $default = null): ?PreparedResponseCacheEntity
    {
        /** @var PreparedResponseCacheEntity $preparedResponseCache */
        $preparedResponseCache = $this->preparedResponseCacheRepository->findOneBy([
            'uniqueName' => $key,
        ]);

        if ($preparedResponseCache instanceof PreparedResponseCacheEntity) {
            $expiresAt = $preparedResponseCache->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $expiresAt - $currentTimestamp;

            if ($ttlTimestamp > 0) {
                $this->delete($preparedResponseCache);
            }
        }

        return ($preparedResponseCache instanceof PreparedResponseCacheEntity) ?
            $preparedResponseCache :
            null;
    }
    /**
     * @param string $key
     * @param string $value
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(
        string $key,
        string $value
    ) {
        $cache = $this->createPreparedResponseCache(
            $key,
            $value,
            Util::toDateTime()->getTimestamp() + $this->cacheTtl
        );

        $this->preparedResponseCacheRepository->persistAndFlush($cache);
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
     * @param $key
     * @return bool
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($key): bool
    {
        if (!$key instanceof PreparedResponseCacheEntity) {
            $preparedResponseCache = $this->preparedResponseCacheRepository->findOneBy([
                'uniqueName' => $key
            ]);

            if (!$preparedResponseCache instanceof PreparedResponseCacheEntity) {
                $message = sprintf(
                    'SearchCache with unique name %s could not be found',
                    $key
                );

                throw new CacheException($message);
            }

            $this->preparedResponseCacheRepository->getManager()->remove($preparedResponseCache);
            $this->preparedResponseCacheRepository->getManager()->flush();

            return true;
        }

        if ($key instanceof PreparedResponseCacheEntity) {
            $this->preparedResponseCacheRepository->getManager()->persist($key);
            $this->preparedResponseCacheRepository->getManager()->flush();

            return true;
        }

        return false;
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
     * @param string $uniqueName
     * @param int $page
     * @param string $value
     * @param int $ttl
     * @return PreparedResponseCacheEntity
     */
    private function createPreparedResponseCache(
        string $uniqueName,
        string $value,
        int $ttl
    ): PreparedResponseCacheEntity {
        return new PreparedResponseCacheEntity(
            $uniqueName,
            $value,
            $ttl
        );
    }
}