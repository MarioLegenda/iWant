<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;

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
     * @var string|null $shippingServiceName
     */
    private $shippingServiceName;
    /**
     * @var string $shippingType
     */
    private $shippingType;
    /**
     * @var BasePrice $importCharge
     */
    private $importCharge;
    /**
     * @var BasePrice $insuranceCost
     */
    private $insuranceCost;
    /**
     * @var TypeInterface $insuranceOption
     */
    private $insuranceOption;
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
    public function getInsuranceCost(): ?BasePrice
    {
        if ($this->insuranceCost === null) {
            if (!empty($this->simpleXml->InsuranceCost)) {
                $this->insuranceCost = new BasePrice($this->simpleXml->InsuranceCost);
            }
        }

        return $this->insuranceCost;
    }
    /**
     * @return TypeInterface|null
     */
    public function getInsuranceOption(): ?TypeInterface
    {
        if ($this->insuranceOption === null) {
            if (!empty($this->simpleXml->InsuranceOption)) {
                $insuranceOption = (string) $this->simpleXml->InsuranceOption;

                $this->insuranceOption = InsuranceOptionCodeType::fromValue($insuranceOption);
            }
        }

        return $this->insuranceOption;
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
            'insuranceCost' => ($this->getInsuranceCost() instanceof BasePrice) ? $this->getInsuranceCost()->toArray() : null,
            'insuranceOption' => ($this->getInsuranceOption() instanceof TypeInterface) ? (string) $this->getInsuranceOption() : null,
        ];
    }
}