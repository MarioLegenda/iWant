<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class Root implements ArrayNotationInterface
{
    /**
     * @var string $ack
     */
    private $ack;
    /**
     * @var string $timestamp
     */
    private $timestamp;
    /**
     * @var string $version
     */
    private $version;
    /**
     * Root constructor.
     * @param string $ack
     * @param string $timestamp
     * @param string $version
     */
    public function __construct(
        string $ack,
        string $timestamp,
        string $version
    ) {
        $this->ack = $ack;
        $this->timestamp = $timestamp;
        $this->version = $version;
    }
    /**
     * @return string
     */
    public function getAck(): string
    {
        return $this->ack;
    }
    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }
    /**
     * @return \DateTime
     */
    public function getTimestampAsObject(): \DateTime
    {
        return Util::toDateTime($this->getTimestamp(), Util::getDateTimeApplicationFormat());
    }
    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getAck() === 'Success' OR $this->getAck() === 'Warning';
    }
    /**
     * @return bool
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'ack' => $this->getAck(),
            'version' => $this->getVersion(),
            'timestamp' => $this->getTimestamp(),
            'dateTime' => Util::formatFromDate($this->getTimestampAsObject()),
        ];
    }
}