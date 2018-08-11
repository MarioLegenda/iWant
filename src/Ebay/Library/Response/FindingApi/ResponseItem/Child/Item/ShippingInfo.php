<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class ShippingInfo extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var array $shippingServiceCost
     */
    private $shippingServiceCost;
    /**
     * @var bool $expeditedShipping
     */
    private $expeditedShipping;
    /**
     * @var int $handlingTime
     */
    private $handlingTime;
    /**
     * @var bool $oneDayShippingAvailable
     */
    private $oneDayShippingAvailable;
    /**
     * @var string $shippingType
     */
    private $shippingType;
    /**
     * @var array $shipToLocations
     */
    private $shipToLocations;
    /**
     * @param mixed $default
     * @return array
     */
    public function getShippingServiceCost($default = null)
    {
        if ($this->shippingServiceCost === null) {
            if (!empty($this->simpleXml->shippingServiceCost)) {
                $this->setShippingServiceCost(
                    (string) $this->simpleXml->shippingServiceCost['currencyId'],
                    (float) $this->simpleXml->shippingServiceCost
                );
            }
        }

        if ($this->shippingServiceCost === null and $default !== null) {
            return $default;
        }

        return $this->shippingServiceCost;
    }
    /**
     * @param mixed $default
     * @return bool|null
     */
    public function getExpeditedShipping($default = null) : bool
    {
        if ($this->expeditedShipping === null) {
            if (!empty($this->simpleXml->expeditedShipping)) {
                $this->setExpeditedShipping((bool) $this->simpleXml->expeditedShipping);
            }
        }

        if ($this->expeditedShipping === null and $default !== null) {
            return $default;
        }

        return $this->expeditedShipping;
    }

    /**
     * @param mixed $default
     * @return int
     */
    public function getHandlingTime($default = null)
    {
        if ($this->handlingTime === null) {
            if (!empty($this->simpleXml->handlingTime)) {
                $this->setHandlingTime((int) $this->simpleXml->handlingTime);
            }
        }

        if ($this->handlingTime === null and $default !== null) {
            return $default;
        }

        return $this->handlingTime;
    }
    /**
     * @param mixed $default
     * @return int
     */
    public function getOneDayShippingAvailable($default = null) : int
    {
        if ($this->oneDayShippingAvailable === null) {
            if (!empty($this->simpleXml->oneDayShippingAvailable)) {
                $this->setOneDayShippingAvailable((bool) $this->simpleXml->oneDayShippingAvailable);
            }
        }

        if ($this->oneDayShippingAvailable === null and $default !== null) {
            return $default;
        }

        return $this->oneDayShippingAvailable;
    }
    /**
     * @param mixed $default
     * @return string
     */
    public function getShippingType($default = null)
    {
        if ($this->shippingType === null) {
            if (!empty($this->simpleXml->shippingType)) {
                $this->setShippingType((string) $this->simpleXml->shippingType);
            }
        }

        if ($this->shippingType === null and $default !== null) {
            return $default;
        }

        return $this->shippingType;
    }
    /**
     * @param mixed $default
     * @return array
     */
    public function getShipToLocations($default = null)
    {
        if ($this->shipToLocations === null) {
            if (!empty($this->simpleXml->shipToLocations)) {
                $this->setShipToLocations($this->simpleXml->shipToLocations);
            }
        }

        if ($this->shipToLocations === null and $default !== null) {
            return $default;
        }

        return $this->shipToLocations;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'shippingServiceCost' => $this->getShippingServiceCost(),
            'shipToLocations' => $this->getShipToLocations(),
            'expeditedShipping' => $this->getExpeditedShipping(),
            'handlingTime' => $this->getHandlingTime(),
            'oneDayShippingAvailable' => $this->getOneDayShippingAvailable(),
            'shippingType' => $this->getShippingType(),
        );
    }
    /**
     * @param $locations
     * @return ShippingInfo
     */
    private function setShipToLocations($locations) : ShippingInfo
    {
        foreach ($locations as $location) {
            $this->shipToLocations[] = (string) $location;
        }

        return $this;
    }
    /**
     * @param string $shippingType
     * @return ShippingInfo
     */
    private function setShippingType(string $shippingType) : ShippingInfo
    {
        $this->shippingType = $shippingType;

        return $this;
    }
    /**
     * @param bool $oneDayShippingAvailable
     * @return ShippingInfo
     */
    private function setOneDayShippingAvailable(bool $oneDayShippingAvailable) : ShippingInfo
    {
        $this->oneDayShippingAvailable = $oneDayShippingAvailable;

        return $this;
    }
    /**
     * @param int $handlingTime
     * @return ShippingInfo
     */
    private function setHandlingTime(int $handlingTime) : ShippingInfo
    {
        $this->handlingTime = $handlingTime;

        return $this;
    }
    /**
     * @param string $currencyId
     * @param float $amount
     * @return ShippingInfo
     */
    private function setShippingServiceCost(string $currencyId, float $amount) : ShippingInfo
    {
        $this->shippingServiceCost = array(
            'currencyId' => $currencyId,
            'amount' => $amount,
        );

        return $this;
    }
    /**
     * @param bool $expeditedShipping
     * @return ShippingInfo
     */
    private function setExpeditedShipping(bool $expeditedShipping) : ShippingInfo
    {
        $this->expeditedShipping = $expeditedShipping;

        return $this;
    }
}