<?php

namespace App\Ebay\Library\Tools;

class LockedHashSet
{
    /**
     * @var array $keys
     */
    private $keys;
    /**
     * @param array $keys
     * @return LockedHashSet
     */
    public static function create(array $keys)
    {
        return new LockedHashSet($keys);
    }
    /**
     * LockedHashSet constructor.
     * @param array $keys
     */
    private function __construct(array $keys)
    {
        $this->keys = $keys;
    }


}