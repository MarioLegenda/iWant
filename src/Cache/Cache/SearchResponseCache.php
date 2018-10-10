<?php

namespace App\Cache\Cache;

use App\Cache\Exception\CacheException;
use App\Doctrine\Entity\SearchCache;
use App\Doctrine\Repository\SearchCacheRepository;
use App\Library\Util\Util;

class SearchResponseCache
{
    /**
     * @var SearchCacheRepository $searchCacheRepository
     */
    private $searchCacheRepository;
    /**
     * TodaysProductsCache constructor.
     * @param SearchCacheRepository $searchCacheRepository
     */
    public function __construct(
        SearchCacheRepository $searchCacheRepository
    ) {
        $this->searchCacheRepository = $searchCacheRepository;
    }
    /**
     * @param $key
     * @param null $default
     * @return SearchCache|null
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function get($key, $default = null): ?SearchCache
    {
        /** @var SearchCache $searchCache */
        $searchCache = $this->searchCacheRepository->findOneBy([
            'uniqueName' => $key,
        ]);

        if ($searchCache instanceof SearchCache) {
            $expiresAt = $searchCache->getExpiresAt();

            $currentTimestamp = Util::toDateTime()->getTimestamp();

            $ttlTimestamp = $expiresAt - $currentTimestamp;

            if ($ttlTimestamp > 0) {
                $this->delete($searchCache);
            }
        }

        return ($searchCache instanceof SearchCache) ?
            $searchCache :
            null;
    }
    /**
     * @param string $key
     * @param string $value
     * @param null $ttl
     * @throws CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(string $key, string $value, int $ttl = null)
    {
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

        $cache = $this->createSearchCache(
            $key,
            $value,
            $ttl
        );

        $this->searchCacheRepository->persistAndFlush($cache);
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
        if (!$key instanceof SearchCache) {
            $searchCache = $this->searchCacheRepository->findOneBy([
                'uniqueName' => $key
            ]);

            if (!$searchCache instanceof SearchCache) {
                $message = sprintf(
                    'SearchCache with unique name %s could not be found',
                    $key
                );

                throw new CacheException($message);
            }

            $this->searchCacheRepository->getManager()->remove($searchCache);
            $this->searchCacheRepository->getManager()->flush();

            return true;
        }

        if ($key instanceof SearchCache) {
            $this->searchCacheRepository->getManager()->persist($key);
            $this->searchCacheRepository->getManager()->flush();

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
     * @param string $value
     * @param int $ttl
     * @return SearchCache
     */
    private function createSearchCache(
        string $uniqueName,
        string $value,
        int $ttl
    ) {
        return new SearchCache(
            $uniqueName,
            $value,
            $ttl
        );
    }
}