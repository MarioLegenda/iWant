<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class BaseTime implements ArrayNotationInterface
{
    /**
     * @var string $timestamp
     */
    private $timestamp;
    /**
     * @var \DateTime $dateTime
     */
    private $dateTime;
    /**
     * BaseTime constructor.
     * @param string $timestamp
     */
    public function __construct(
        string $timestamp
    ) {
        $this->timestamp = $timestamp;
        $this->dateTime = Util::toDateTime($timestamp);
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
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }
    /**
     * @return string|null
     */
    public function __toString()
    {
        return Util::formatFromDate($this->getDateTime());
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'timestamp' => $this->getTimestamp(),
            'dateTime' => (string) $this,
        ];
    }
}