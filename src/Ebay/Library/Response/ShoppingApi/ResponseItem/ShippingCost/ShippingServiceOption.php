<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BaseTime;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ShippingServiceOption extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var BaseTime|null $estimatedDeliveryMaxTime
     */
    private $estimatedDeliveryMaxTime;
    /**
     * @var BaseTime|null $estimatedDeliveryMinTime
     */
    private $estimatedDeliveryMinTime;
    /**
     * @var bool|null $expeditedService
     */
    private $expeditedService;
    /**
     * @var bool|null $fastAndFree
     */
    private $fastAndFree;
    /**
     * @var BasePrice|null $shippingInsuranceCost
     */
    private $shippingInsuranceCost;
    /**
     * @var BasePrice|$shippingServiceAdditionalCost
     */
    private $shippingServiceAdditionalCost;
    /**
     * @var BasePrice|null
     */
    private $shippingServiceCost;
    /**
     * @var BaseTime|null
     */
    private $shippingServiceCutOffTime;
    /**
     * @var string|null $shippingServiceName
     */
    private $shippingServiceName;
    /**
     * @var int|null
     */
    private $shippingServicePriority;
    /**
     * @var BasePrice|null $shippingSurcharge
     */
    private $shippingSurcharge;
    /**
     * @var int|null $shippingTimeMax
     */
    private $shippingTimeMax;
    /**
     * @var int|null $shippingTimeMin
     */
    private $shippingTimeMin;
    /**
     * @var Location[]|null
     */
    private $shipsTo;
    /**
     * @return array|null
     */
    public function getShipsTo(): ?array
    {
        if ($this->shipsTo === null) {
            if (!empty($this->simpleXml->ShipsTo)) {
                $this->shipsTo = createBulkObjectFromArrayArgs(
                    (array) $this->simpleXml->ShipsTo,
                    Location::class
                );
            }
        }

        return $this->shipsTo;
    }
    /**
     * @return int|null
     */
    public function getShippingTimeMin(): ?int
    {
        if ($this->shippingTimeMin === null) {
            if (!empty($this->simpleXml->ShippingTimeMin)) {
                $this->shippingTimeMin = (int) $this->simpleXml->ShippingTimeMin;
            }
        }

        return $this->shippingTimeMin;
    }
    /**
     * @return int|null
     */
    public function getShippingTimeMax(): ?int
    {
        if ($this->shippingTimeMax === null) {
            if (!empty($this->simpleXml->ShippingTimeMax)) {
                $this->shippingTimeMax = (int) $this->simpleXml->ShippingTimeMax;
            }
        }

        return $this->shippingTimeMax;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingSurcharge(): ?BasePrice
    {
        if ($this->shippingSurcharge === null) {
            if (!empty($this->simpleXml->ShippingSurcharge)) {
                $this->shippingSurcharge = new BasePrice($this->simpleXml->ShippingSurcharge);
            }
        }

        return $this->shippingSurcharge;
    }
    /**
     * @return int|null
     */
    public function getShippingServicePriority(): ?int
    {
        if ($this->shippingServicePriority === null) {
            if (!empty($this->simpleXml->ShippingServicePriority)) {
                $this->shippingServicePriority = (int) $this->simpleXml->ShippingServicePriority;
            }
        }

        return $this->shippingServicePriority;
    }
    /**
     * @return string|null
     */
    public function getShippingServiceName(): ?string
    {
        if ($this->shippingServiceName === null) {
            if (!empty($this->simpleXml->ShippingServiceName)) {
                $this->shippingServiceName = (string) $this->simpleXml->ShippingServiceName;
            }
        }

        return $this->shippingServiceName;
    }
    /**
     * @return BaseTime|null
     */
    public function getShippingServiceCutOffTime(): ?BaseTime
    {
        if ($this->shippingServiceCutOffTime === null) {
            if (!empty($this->simpleXml->ShippingServiceCutOffTime)) {
                $this->shippingServiceCutOffTime = new BaseTime($this->simpleXml->ShippingServiceCutOffTime);
            }
        }

        return $this->shippingServiceCutOffTime;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingServiceCost(): ?BasePrice
    {
        if ($this->shippingServiceCost === null) {
            if (!empty($this->simpleXml->ShippingServiceCost)) {
                $this->shippingServiceCost = new BasePrice($this->simpleXml->ShippingServiceCost);
            }
        }

        return $this->shippingServiceCost;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingServiceAdditionalCost(): ?BasePrice
    {
        if ($this->shippingServiceAdditionalCost === null) {
            if (!empty($this->simpleXml->ShippingServiceAdditionalCost)) {
                $this->shippingServiceAdditionalCost = new BasePrice($this->simpleXml->ShippingServiceAdditionalCost);
            }
        }

        return $this->shippingServiceAdditionalCost;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingInsuranceCost(): ?BasePrice
    {
        if ($this->shippingInsuranceCost === null) {
            if (!empty($this->simpleXml->ShippingInsuranceCost)) {
                $this->shippingInsuranceCost = new BasePrice($this->simpleXml->ShippingInsuranceCost);
            }
        }

        return $this->shippingInsuranceCost;
    }
    /**
     * @return bool|null
     */
    public function getFastAndFree(): ?bool
    {
        if ($this->fastAndFree === null) {
            if (!empty($this->simpleXml->FastAndFree)) {
                $this->fastAndFree = stringToBool($this->simpleXml->FastAndFree);
            }
        }

        return $this->fastAndFree;
    }
    /**
     * @return bool|null
     */
    public function getExpeditedService(): ?bool
    {
        if ($this->expeditedService === null) {
            if (!empty($this->simpleXml->ExpeditedService)) {
                $this->expeditedService = stringToBool($this->simpleXml->ExpeditedService);
            }
        }

        return $this->expeditedService;
    }
    /**
     * @return BaseTime|null
     */
    public function getEstimatedDeliveryMaxTime(): ?BaseTime
    {
        if ($this->estimatedDeliveryMaxTime === null) {
            if (!empty($this->simpleXml->EstimatedDeliveryMaxTime)) {
                $this->estimatedDeliveryMaxTime = new BaseTime($this->simpleXml->EstimatedDeliveryMaxTime);
            }
        }

        return $this->estimatedDeliveryMaxTime;
    }
    /**
     * @return BaseTime|null
     */
    public function getEstimatedDeliveryMinTime(): ?BaseTime
    {
        if ($this->estimatedDeliveryMinTime === null) {
            if (!empty($this->simpleXml->EstimatedDeliveryMinTime)) {
                $this->estimatedDeliveryMinTime = new BaseTime($this->simpleXml->EstimatedDeliveryMinTime);
            }
        }

        return $this->estimatedDeliveryMinTime;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        $shipsTo = (function() {
            if (!is_array($this->getShipsTo())) {
                return null;
            }

            return apply_on_iterable($this->getShipsTo(), function(Location $item) {
                return $item->toArray();
            });
        })();

        return [
            'estimatedDeliveryMaxTime' => $this->getEstimatedDeliveryMaxTime(),
            'estimatedDeliveryMinTime' => $this->getEstimatedDeliveryMinTime(),
            'expeditedService' => $this->getExpeditedService(),
            'fastAndFree' => $this->getFastAndFree(),
            'shippingInsuranceCost' => ($this->getShippingInsuranceCost() instanceof BasePrice) ? $this->getShippingInsuranceCost()->toArray() : null,
            'shippingServiceAdditionalCost' => ($this->getShippingServiceAdditionalCost() instanceof BasePrice) ? $this->getShippingServiceAdditionalCost()->toArray() : null,
            'shippingServiceCost' => ($this->getShippingServiceCost() instanceof BasePrice) ? $this->getShippingServiceCost()->toArray() : null,
            'shippingServiceCutOffTime' => ($this->getShippingServiceCutOffTime() instanceof BaseTime) ? $this->getShippingServiceCutOffTime()->toArray() : null,
            'shippingServiceName' => $this->getShippingServiceName(),
            'shippingServicePriority' => $this->getShippingServicePriority(),
            'shippingSurcharge' => ($this->getShippingSurcharge() instanceof BasePrice) ? $this->getShippingSurcharge()->toArray() : null,
            'shippingTimeMax' => $this->getShippingTimeMax(),
            'shippingTimeMin' => $this->getShippingTimeMin(),
            'shipsTo' => $shipsTo,
        ];
    }
}