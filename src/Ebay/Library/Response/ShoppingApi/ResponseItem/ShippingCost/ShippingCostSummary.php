<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ShippingCostSummary extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var ListedShippingServiceCost $listedShippingServiceCost
     */
    private $listedShippingServiceCost;
    /**
     * @var ShippingServiceCost $shippingServiceCost
     */
    private $shippingServiceCost;
    /**
     * @var string $shippingServiceName
     */
    private $shippingServiceName;
    /**
     * @var string $shippingType
     */
    private $shippingType;
    /**
     * @return ListedShippingServiceCost
     */
    public function getListedShippingServiceCost(): ListedShippingServiceCost
    {
        if ($this->listedShippingServiceCost === null) {
            if (!empty($this->simpleXml->ListedShippingServiceCost)) {
                $this->listedShippingServiceCost = new ListedShippingServiceCost($this->simpleXml->ListedShippingServiceCost);
            }
        }

        return $this->listedShippingServiceCost;
    }
    /**
     * @return ShippingServiceCost
     */
    public function getShippingServiceCost(): ShippingServiceCost
    {
        if ($this->shippingServiceCost === null) {
            if (!empty($this->simpleXml->ShippingServiceCost)) {
                $this->shippingServiceCost = new ShippingServiceCost($this->simpleXml->ShippingServiceCost);
            }
        }

        return $this->shippingServiceCost;
    }
    /**
     * @return string
     */
    public function getShippingServiceName(): string
    {

        if ($this->shippingServiceName === null) {
            if (!empty($this->simpleXml->ShippingServiceName)) {
                $this->shippingServiceName = (string) $this->simpleXml->ShippingServiceName;
            }
        }

        return $this->shippingServiceName;
    }
    /**
     * @return string
     */
    public function getShippingType(): string
    {
        if ($this->shippingType === null) {
            if (!empty($this->simpleXml->ShippingType)) {
                $this->shippingType = (string) $this->simpleXml->ShippingType;
            }
        }

        return $this->shippingType;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'shippingServiceName' => $this->getShippingServiceName(),
            'listedShippingServiceCost' => $this->getListedShippingServiceCost()->toArray(),
            'shippingServiceCost' => $this->getShippingServiceCost()->toArray(),
            'shippingType' => $this->getShippingType(),
        ];
    }
}