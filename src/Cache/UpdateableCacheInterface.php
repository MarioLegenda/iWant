<?php

namespace App\Cache;

interface UpdateableCacheInterface
{
    /**
     * @param string $key
     * @param int $page
     * @param string $value
     */
    public function update(string $key, int $page, string $value): void;
}