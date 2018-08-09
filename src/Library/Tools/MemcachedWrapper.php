<?php

namespace App\Library\Tools;

class MemcachedWrapper
{
    const RESPONSE_CACHE = 'app.response_cache';
    /**
     * @var \Memcached $memcached
     */
    private $memcached;
    /**
     * MemcachedWrapper constructor.
     */
    public function __construct()
    {
        $this->memcached = new \Memcached(MemcachedWrapper::RESPONSE_CACHE);

        $this->memcached->setOptions(array(
            \Memcached::OPT_NO_BLOCK => true,
            \Memcached::OPT_TCP_NODELAY => true,
            \Memcached::OPT_BUFFER_WRITES => true,
            \Memcached::OPT_BINARY_PROTOCOL => true,
            \Memcached::OPT_LIBKETAMA_COMPATIBLE => true,
            \Memcached::OPT_DISTRIBUTION => \Memcached::DISTRIBUTION_CONSISTENT,
        ));
    }
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $this->memcached->get($key);

        return $this->memcached->getResultCode() === \Memcached::RES_SUCCESS;
    }
    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $this->memcached->get($key);
    }
    /**
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value): void
    {
        $this->memcached->set($key, $value);
    }
    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        $this->memcached->delete($key);
    }
}