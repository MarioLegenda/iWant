<?php

namespace App\Cache\Cache;

use App\Cache\CacheInterface;
use App\Cache\Exception\CacheException;
use App\Doctrine\Entity\RequestCache;
use App\Doctrine\Repository\RequestCacheRepository;

class ApiRequestCache implements CacheInterface
{
    /**
     * @var RequestCacheRepository $requestCacheRepository
     */
    private $requestCacheRepository;
    /**
     * ApiRequestCache constructor.
     * @param RequestCacheRepository $requestCacheRepository
     */
    public function __construct(
        RequestCacheRepository $requestCacheRepository
    ) {
        $this->requestCacheRepository = $requestCacheRepository;
    }
    /**
     * @inheritdoc
     */
    public function clear()
    {
        $em = $this->requestCacheRepository->getManager();

        $conn = $em->getConnection();

        $conn->exec('DELETE FROM request_cache');
    }
    /**
     * @inheritdoc
     */
    public function delete($key)
    {
        $cache = $this->requestCacheRepository->findOneBy([
            'request' => $key
        ]);

        if ($cache instanceof RequestCache) {
            $this->requestCacheRepository->getManager()->remove($cache);
            $this->requestCacheRepository->getManager()->flush();
        }
    }
    /**
     * @inheritdoc
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $cache = $this->requestCacheRepository->findOneBy([
                'request' => $key
            ]);

            if (!$cache instanceof RequestCache) {
                $this->requestCacheRepository->getManager()->remove($cache);
            }
        }

        $this->requestCacheRepository->getManager()->flush();
    }
    /**
     * @inheritdoc
     */
    public function get($key, $default = null): ?RequestCache
    {
        $cache = $this->requestCacheRepository->findOneBy([
            'request' => $key,
        ]);

        if (!$cache instanceof RequestCache) {
            return null;
        }

        return $cache;
    }
    /**
     * @inheritdoc
     */
    public function getMultiple($keys, $default = null)
    {

    }
    /**
     * @inheritdoc
     */
    public function has($key)
    {
        $message = sprintf(
            '%s::has() is not implemented and cannot be used',
            get_class($this)
        );

        throw new CacheException($message);
    }
    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = null): bool
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

        $requestCache = $this->createRequestCache(
            $key,
            $value,
            $ttl
        );

        $this->requestCacheRepository->getManager()->persist($requestCache);
        $this->requestCacheRepository->getManager()->flush();

        return true;
    }
    /**
     * @inheritdoc
     */
    public function setMultiple($values, $ttl = null)
    {
    }
    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return RequestCache
     */
    private function createRequestCache(
        string $key,
        string $value,
        int $ttl
    ): RequestCache {
        return new RequestCache(
            $key,
            $value,
            $ttl
        );
    }
}