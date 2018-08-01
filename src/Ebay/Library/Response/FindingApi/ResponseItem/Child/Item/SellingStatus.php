<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class SellingStatus extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var int $bidCount
     */
    private $bidCount;
    /**
     * @var float $convertedCurrentPrice
     */
    private $convertedCurrentPrice;
    /**
     * @var array $currentPrice
     */
    private $currentPrice;
    /**
     * @var string $sellingState
     */
    private $sellingState;
    /**
     * @var string $timeLeft
     */
    private $timeLeft;
    /**
     * @param
     * @return int|null
     */
    public function getBidCount($default = null)
    {
        if ($this->bidCount === null) {
            if (!empty($this->simpleXml->bidCount)) {
                $this->setBidCount((int) $this->simpleXml->bidCount);
            }
        }

        if ($this->bidCount === null and $default !== null) {
            return $default;
        }

        return $this->bidCount;
    }
    /**
     * @param mixed $default
     * @return float|null
     */
    public function getConvertedCurrentPrice($default = null)
    {
        if ($this->convertedCurrentPrice === null) {
            if (!empty($this->simpleXml->convertedCurrentPrice)) {
                $this->setConvertedCurrentPrice((string) $this->simpleXml->convertedCurrentPrice['currencyId'], (float) $this->simpleXml->convertedCurrentPrice);
            }
        }

        if ($this->convertedCurrentPrice === null and $default !== null) {
            return $default;
        }

        return $this->convertedCurrentPrice;
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function getCurrentPrice($default = null)
    {
        if ($this->currentPrice === null) {
            if (!empty($this->simpleXml->currentPrice)) {
                $this->setCurrentPrice((string) $this->simpleXml->currentPrice['currencyId'], (float) $this->simpleXml->currentPrice);
            }
        }

        if ($this->currentPrice === null and $default !== null) {
            return $default;
        }

        return $this->currentPrice;
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getSellingState($default = null)
    {
        if ($this->sellingState === null) {
            if (!empty($this->simpleXml->sellingState)) {
                $this->setSellingState((string) $this->simpleXml->sellingState);
            }
        }

        if ($this->sellingState === null and $default !== null) {
            return $default;
        }

        return $this->sellingState;
    }
    /**
     * @param mixed $default
     * @return int|null
     */
    public function getTimeLeft($default = null)
    {
        if ($this->timeLeft === null) {
            if (!empty($this->simpleXml->timeLeft)) {
                $this->setTimeLeft((string) $this->simpleXml->timeLeft);
            }
        }

        if ($this->timeLeft === null and $default !== null) {
            return $default;
        }

        return $this->timeLeft;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'currentPrice' => $this->getCurrentPrice(),
            'bidCount' => $this->getBidCount(),
            'convertedCurrentPrice' => $this->getConvertedCurrentPrice(),
            'sellingState' => $this->getSellingState(),
            'timeLeft' => $this->getTimeLeft(),
        );
    }

    private function setTimeLeft($timeLeft) : SellingStatus
    {
        $this->timeLeft = $timeLeft;

        return $this;
    }

    private function setSellingState($sellingState) : SellingStatus
    {
        $this->sellingState = $sellingState;

        return $this;
    }

    private function setCurrentPrice(string $currencyId, float $currentPrice) : SellingStatus
    {
        $this->currentPrice = array(
            'currencyId' => $currencyId,
            'currentPrice' => $currentPrice,
        );

        return $this;
    }

    private function setConvertedCurrentPrice($currencyId, $convertedCurrentPrice)
    {
        $this->convertedCurrentPrice = array(
            'currencyId' => $currencyId,
            'convertedCurrentPrice' => $convertedCurrentPrice,
        );

        return $this;
    }

    private function setBidCount($bidCount) : SellingStatus
    {
        $this->bidCount = $bidCount;

        return $this;
    }
}