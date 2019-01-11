<?php

namespace App\Ebay\Library\Response\FindingApi\Json;

use App\Ebay\Library\Response\RootItemInterface;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class Root implements RootItemInterface, ArrayNotationInterface
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
     * @var string|null $itemSearchUrl
     */
    private $itemSearchUrl;
    /**
     * Root constructor.
     * @param string $ack
     * @param string $timestamp
     * @param string $version
     * @param string|null $itemSearchUrl
     */
    public function __construct(
        string $ack,
        string $timestamp,
        string $version,
        ?string $itemSearchUrl
    ) {
        $this->ack = $ack;
        $this->timestamp = $timestamp;
        $this->version = $version;
        $this->itemSearchUrl = $itemSearchUrl;
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
     * @return string|null
     */
    public function getItemSearchUrl(): ?string
    {
        return $this->itemSearchUrl;
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
            'timestamp' => $this->getTimestamp(),
            'version' => $this->getVersion(),
            'itemSearchUrl' => $this->getItemSearchUrl(),
        ];
    }
}