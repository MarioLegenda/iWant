<?php

namespace App\Ebay\Library\Response;

interface RootItemInterface
{
    /**
     * @return string
     */
    public function getNamespace(): string;
    /**
     * @return mixed
     */
    public function getVersion();
    /**
     * @return mixed
     */
    public function getTimestamp();
    /**
     * @return string
     */
    public function getAck(): string;
    /**
     * @return bool
     */
    public function isSuccess(): bool;
}