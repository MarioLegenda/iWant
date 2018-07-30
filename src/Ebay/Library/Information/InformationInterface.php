<?php

namespace App\Ebay\Library\Information;

interface InformationInterface
{
    /**
     * @param string $entry
     * @return bool
     */
    public function has(string $entry) : bool;
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry) : bool;
    /**
     * @return array
     */
    public function getAll() : array;
}