<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class ListingInfo extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var bool $bestOfferEnabled
     */
    private $bestOfferEnabled;
    /**
     * @var bool $buyItNowAvailable
     */
    private $buyItNowAvailable;
    /**
     * @var array $buyItNowPrice
     */
    private $buyItNowPrice;
    /**
     * @var array $convertedBuyItNowPrice
     */
    private $convertedBuyItNowPrice;
    /**
     * @var \DateTime $endTime
     */
    private $endTime;
    /**
     * @var bool $gift
     */
    private $gift;
    /**
     * @var string $listingType
     */
    private $listingType;
    /**
     * @var \DateTime $startTime
     */
    private $startTime;
    /**
     * @return bool|null
     */
    public function getBestOfferEnabled($default = null)
    {
        if ($this->bestOfferEnabled === null) {
            if (!empty($this->simpleXml->bestOfferEnabled)) {
                $this->setBestOfferEnabled((bool) $this->simpleXml->bestOfferEnabled);
            }
        }

        if ($this->bestOfferEnabled === null and $default !== null) {
            return $default;
        }

        return $this->bestOfferEnabled;
    }
    /**
     * @param mixed $default
     * @return bool|null
     */
    public function getBuyItNowAvailable($default = null)
    {
        if ($this->buyItNowAvailable === null) {
            if (!empty($this->simpleXml->buyItNowAvailable)) {
                $this->setBuyItNowAvailable((bool) $this->simpleXml->buyItNowAvailable);
            }
        }

        if ($this->buyItNowAvailable === null and $default !== null) {
            return $default;
        }


        return $this->buyItNowAvailable;
    }
    /**
     * @param mixed $default
     * @return array|null
     */
    public function getBuyItNowPrice($default = null)
    {
        if ($this->buyItNowPrice === null) {
            if (!empty($this->simpleXml->buyItNowPrice)) {
                $this->setBuyItNowPrice((string) $this->simpleXml->buyItNowPrice['currencyId'], (float) $this->simpleXml->buyItNowPrice);
            }
        }

        if ($this->buyItNowPrice === null and $default !== null) {
            return $default;
        }


        return $this->buyItNowPrice;
    }
    /**
     * @param mixed $default
     * @return array|null
     */
    public function getConvertedBuyItNowPrice($default = null)
    {
        if ($this->convertedBuyItNowPrice === null) {
            if (!empty($this->simpleXml->convertedBuyItNowPrice)) {
                $this->setConvertedBuyItNowPrice((string) $this->simpleXml->convertedBuyItNowPrice['currencyId'], (float) $this->simpleXml->convertedBuyItNowPrice);
            }
        }

        if ($this->convertedBuyItNowPrice === null and $default !== null) {
            return $default;
        }

        return $this->convertedBuyItNowPrice;
    }
    /**
     * @param mixed $default
     * @return \DateTime|null
     */
    public function getEndTime($default = null)
    {
        if ($this->endTime === null) {
            if (!empty($this->simpleXml->endTime)) {
                $this->setEndTime((string) $this->simpleXml->endTime);
            }
        }

        if ($this->endTime === null and $default !== null) {
            return $default;
        }

        return $this->endTime;
    }

    /**
     * @param mixed $default
     * @return bool|null
     */
    public function getGift($default = null)
    {
        if ($this->gift === null) {
            if (!empty($this->simpleXml->gift)) {
                $this->setGift((bool) $this->simpleXml->gift);
            }
        }

        if ($this->gift === null and $default !== null) {
            return $default;
        }

        return $this->gift;
    }
    /**
     * @param mixed $default
     * @return null|string
     */
    public function getListingType($default = null)
    {
        if ($this->listingType === null) {
            if (!empty($this->simpleXml->listingType)) {
                $this->setListingType((string) $this->simpleXml->listingType);
            }
        }

        if ($this->listingType === null and $default !== null) {
            return $default;
        }

        return $this->listingType;
    }
    /**
     * @param mixed $default
     * @return \DateTime|null
     */
    public function getStartTime($default = null)
    {
        if ($this->startTime === null) {
            if (!empty($this->simpleXml->startTime)) {
                $this->setStartTime((string) $this->simpleXml->startTime);
            }
        }

        if ($this->startTime === null and $default !== null) {
            return $default;
        }

        return $this->startTime;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'bestOfferEnabled' => $this->getBestOfferEnabled(),
            'buyItNowPrice' => $this->getBuyItNowPrice(),
            'convertedBuyItNowPrice' => $this->getConvertedBuyItNowPrice(),
            'buyItNowAvailable' => $this->getBuyItNowAvailable(),
            'endTime' => $this->getEndTime(),
            'gift' => $this->getGift(),
            'listingType' => $this->getListingType(),
            'startTime' => $this->getStartTime(),
        );
    }
    /**
     * @param string $startTime
     * @return ListingInfo
     */
    private function setStartTime(string $startTime) : ListingInfo
    {
        $this->startTime = $startTime;

        return $this;
    }
    /**
     * @param string $listingType
     * @return ListingInfo
     */
    private function setListingType(string $listingType) : ListingInfo
    {
        $this->listingType = $listingType;

        return $this;
    }
    /**
     * @param bool $gift
     * @return ListingInfo
     */
    private function setGift(bool $gift) : ListingInfo
    {
        $this->gift = $gift;

        return $this;
    }
    /**
     * @param string $endTime
     * @return ListingInfo
     */
    private function setEndTime(string $endTime) : ListingInfo
    {
        $this->endTime = $endTime;

        return $this;
    }
    /**
     * @param string $currencyId
     * @param float $amount
     * @return ListingInfo
     */
    private function setConvertedBuyItNowPrice(string $currencyId, float $amount) : ListingInfo
    {
        $this->convertedBuyItNowPrice = array(
            'currencyId' => $currencyId,
            'amount' => $amount,
        );

        return $this;
    }
    /**
     * @param string $currencyId
     * @param float $amount
     * @return ListingInfo
     */
    private function setBuyItNowPrice(string $currencyId, float $amount) : ListingInfo
    {
        $this->buyItNowPrice = array(
            'currencyId' => $currencyId,
            'amount' => $amount,
        );

        return $this;
    }
    /**
     * @param $buyItNowAvailable
     * @return ListingInfo
     */
    private function setBuyItNowAvailable($buyItNowAvailable) : ListingInfo
    {
        $this->buyItNowAvailable = $buyItNowAvailable;

        return $this;
    }
    /**
     * @param $bestOfferEnabled
     * @return ListingInfo
     */
    private function setBestOfferEnabled($bestOfferEnabled) : ListingInfo
    {
        $this->bestOfferEnabled = $bestOfferEnabled;

        return $this;
    }
}