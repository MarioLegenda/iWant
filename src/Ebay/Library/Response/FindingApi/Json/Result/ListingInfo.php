<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;


use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class ListingInfo implements ArrayNotationInterface
{
    /**
     * @var bool
     */
    private $bestOfferEnabled;
    /**
     * @var bool
     */
    private $buyItNowAvailable;
    /**
     * @var string
     */
    private $startTime;
    /**
     * @var string
     */
    private $endTime;
    /**
     * @var string
     */
    private $listingType;
    /**
     * @var bool
     */
    private $gift;
    /**
     * @var int
     */
    private $watchCount;
    /**
     * ListingInfo constructor.
     * @param bool $bestOfferEnabled
     * @param bool $buyItNowAvailable
     * @param string $startTime
     * @param string $endTime
     * @param string $listingType
     * @param bool $gift
     * @param int|null $watchCount
     */
    public function __construct(
        bool $bestOfferEnabled,
        bool $buyItNowAvailable,
        string $startTime,
        string $endTime,
        string $listingType,
        bool $gift,
        ?int $watchCount
    ) {
        $this->bestOfferEnabled = $bestOfferEnabled;
        $this->buyItNowAvailable = $buyItNowAvailable;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->listingType = $listingType;
        $this->gift = $gift;
        $this->watchCount = $watchCount;
    }
    /**
     * @return bool
     */
    public function isBestOfferEnabled(): bool
    {
        return $this->bestOfferEnabled;
    }
    /**
     * @return bool
     */
    public function isBuyItNowAvailable(): bool
    {
        return $this->buyItNowAvailable;
    }
    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }
    /**
     * @return string
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }
    /**
     * @return string
     */
    public function getListingType(): string
    {
        return $this->listingType;
    }
    /**
     * @return bool
     */
    public function isGift(): bool
    {
        return $this->gift;
    }
    /**
     * @return int|null
     */
    public function getWatchCount(): ?int
    {
        return $this->watchCount;
    }
    /**
     * @return \DateTime
     */
    public function getStartTimeAsObject(): \DateTime
    {
        return Util::toDateTime($this->getStartTime(), Util::getDateTimeApplicationFormat());
    }
    /**
     * @return \DateTime
     */
    public function getEndTimeAsObject(): \DateTime
    {
        return Util::toDateTime($this->getEndTime(), Util::getDateTimeApplicationFormat());
    }
    /**
     * @return iterable|array
     */
    public function toArray(): iterable
    {
        return [
            'bestOfferEnabled' => $this->isBestOfferEnabled(),
            'buyItNowAvailable' => $this->isBuyItNowAvailable(),
            'startTime' => Util::formatFromDate($this->getStartTimeAsObject()),
            'endTime' => Util::formatFromDate($this->getEndTimeAsObject()),
            'gift' => $this->isGift(),
            'listingType' => $this->getListingType(),
            'watchCount' => $this->getWatchCount(),
        ];
    }
}