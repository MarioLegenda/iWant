<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ShippingInfo implements ArrayNotationInterface
{
    /**
     * @var string
     */
    private $shippingType;
    /**
     * @var array
     */
    private $shipToLocations;
    /**
     * @var bool|null
     */
    private $expeditedShipping;
    /**
     * @var bool|null
     */
    private $oneDayShippingAvailable;
    /**
     * @var int|null
     */
    private $handlingTime;
    /**
     * ShippingInfo constructor.
     * @param string $shippingType
     * @param array $shipToLocations
     * @param bool $expeditedShipping
     * @param bool $oneDayShippingAvailable
     * @param int $handlingTime
     */
    public function __construct(
        string $shippingType,
        array $shipToLocations,
        ?bool $expeditedShipping,
        ?bool $oneDayShippingAvailable,
        ?int $handlingTime
    ) {
        $this->shippingType = $shippingType;
        $this->shipToLocations = $shipToLocations;
        $this->expeditedShipping = $expeditedShipping;
        $this->oneDayShippingAvailable = $oneDayShippingAvailable;
        $this->handlingTime = $handlingTime;
    }
    /**
     * @return string
     */
    public function getShippingType(): string
    {
        return $this->shippingType;
    }
    /**
     * @return array
     */
    public function getShipToLocations(): array
    {
        return $this->shipToLocations;
    }
    /**
     * @return bool|null
     */
    public function isExpeditedShipping(): ?bool
    {
        return $this->expeditedShipping;
    }
    /**
     * @return bool|null
     */
    public function isOneDayShippingAvailable(): ?bool
    {
        return $this->oneDayShippingAvailable;
    }
    /**
     * @return int|null
     */
    public function getHandlingTime(): ?int
    {
        return $this->handlingTime;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return (array) $this;
    }
}