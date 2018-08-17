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

        $conn->exec('TRUNCATE TABLE request_cache');
    }
    /**
     * @inheritdoc
     */
    public function delete($key)
    {
        $cache = $this->requestCacheRepository->findOneBy([
            'request' => $key
        ]);

        if (!$cache instanceof RequestCache) {
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
    public function get($key, $default = null)
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
    public function set($key, $value, $ttl = null)
    {
        if (empty($ttl)) {
            $message = sprintf(
                'TTL has to be implemented in %s::set()',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }


    }
    /**
     * @inheritdoc
     */
    public function setMultiple($values, $ttl = null)
    {
    }
}