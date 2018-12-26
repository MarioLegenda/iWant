<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BaseTime;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class InternationalShippingServiceOption extends AbstractItem implements ArrayNotationInterface
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
     * @var BasePrice|null $importCharge
     */
    private $importCharge;
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
     * @var string|null
     */
    private $shippingServiceName;
    /**
     * @var int|null
     */
    private $shippingServicePriority;
    /**
     * @var Location[]|null
     */
    private $shipsTo;
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
     * @return BasePrice|null
     */
    public function getImportCharge(): ?BasePrice
    {
        if ($this->importCharge === null) {
            if (!empty($this->simpleXml->ImportCharge)) {
                $this->importCharge = new BasePrice($this->simpleXml->ImportCharge);
            }
        }

        return $this->importCharge;
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'estimatedDeliveryMaxTime' => $this->getEstimatedDeliveryMaxTime()->toArray(),
            'estimatedDeliveryMinTime' => $this->getEstimatedDeliveryMinTime()->toArray(),
            'importCharge' => ($this->getImportCharge() instanceof BasePrice) ? $this->getImportCharge()->toArray() : null,
            'shippingInsuranceCost' => ($this->getShippingInsuranceCost() instanceof BasePrice) ? $this->getShippingInsuranceCost()->toArray() : null,
            'shippingServiceAdditionalCost' => $this->getShippingServiceAdditionalCost()->toArray(),
            'shippingServiceCost' => $this->getShippingServiceCost()->toArray(),
            'shippingServiceCutOffTime' => ($this->getShippingServiceCutOffTime() instanceof BaseTime) ? $this->getShippingServiceCutOffTime()->toArray() : null,
            'shippingServiceName' => $this->getShippingServiceName(),
            'shippingServicePriority' => $this->getShippingServicePriority(),
            'shipsTo' => (function() {
                if (!is_array($this->getShipsTo())) {
                    return null;
                }

                return apply_on_iterable($this->getShipsTo(), function(Location $item) {
                    return $item->toArray();
                });
            })(),
        ];
    }
}