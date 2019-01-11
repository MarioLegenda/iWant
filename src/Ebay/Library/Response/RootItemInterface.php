<?php

namespace App\Ebay\Library\Response;

interface RootItemInterface
{
    /**
     * @return string
     */
    public function getVersion(): string;
    /**
     * @return string
     */
    public function getTimestamp(): string;
    /**
     * @return \DateTime
     */
    public function getTimestampAsObject(): \DateTime;
    /**
     * @return string
     */
    public function getAck(): string;
    /**
     * @return bool
     */
    public function isSuccess(): bool;
}