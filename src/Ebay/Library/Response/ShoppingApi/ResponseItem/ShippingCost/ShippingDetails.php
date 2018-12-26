<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;

class ShippingDetails extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var BasePrice|null $cashOnDeliveryCost
     */
    private $cashOnDeliveryCost;
    /**
     * @var array|null $excludeShipToLocations
     */
    private $excludeShipToLocations;
    /**
     * @var BasePrice|null $insuranceCost
     */
    private $insuranceCost;
    /**
     * @var TypeInterface|null $insuranceOption
     */
    private $insuranceOption;
    /**
     * @var BasePrice|null $internationalInsuranceCost
     */
    private $internationalInsuranceCost;
    /**
     * @var InternationalShippingServiceOption $internationalInsuranceOption
     */
    private $internationalInsuranceOption;
    /**
     * @var InternationalShippingServiceOption $internationalShippingServiceOption
     */
    private $internationalShippingServiceOption;
    /**
     * @var SalesTax $salesTax
     */
    private $salesTax;
    /**
     * @return SalesTax|null
     */
    public function getSalesTax(): ?SalesTax
    {
        if ($this->salesTax === null) {
            if (!empty($this->simpleXml->SalesTax)) {
                $this->salesTax = new SalesTax($this->simpleXml->SalesTax);
            }
        }

        return $this->salesTax;
    }
    /**
     * @return InternationalShippingServiceOption|null
     */
    public function getInternationalShippingServiceOption(): ?InternationalShippingServiceOption
    {
        if ($this->internationalShippingServiceOption === null) {
            if (!empty($this->simpleXml->InternationalShippingServiceOption)) {
                $this->internationalShippingServiceOption = new InternationalShippingServiceOption($this->simpleXml->InternationalShippingServiceOption);
            }
        }

        return $this->internationalShippingServiceOption;
    }
    /**
     * @return TypeInterface|null
     */
    public function getInternationalInsuranceOption(): ?TypeInterface
    {
        if ($this->internationalInsuranceOption === null) {
            if (!empty($this->simpleXml->InsuranceOption)) {
                $internationalInsuranceOption = (string) $this->simpleXml->InternationalInsuranceOption;

                $this->internationalInsuranceOption = InsuranceOptionCodeType::fromValue($internationalInsuranceOption);
            }
        }

        return $this->internationalInsuranceOption;
    }
    /**
     * @return BasePrice|null
     */
    public function getInternationalInsuranceCost(): ?BasePrice
    {
        if ($this->internationalInsuranceCost === null) {
            if (!empty($this->simpleXml->InternationalInsuranceCost)) {
                $this->internationalInsuranceCost = new BasePrice($this->simpleXml->InternationalInsuranceCost);
            }
        }

        return $this->internationalInsuranceCost;
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
     * @return array|null
     */
    public function getExcludeShipToLocations(): ?array
    {
        if ($this->excludeShipToLocations === null) {
            if (!empty($this->simpleXml->ExcludeShipToLocation)) {
                $this->excludeShipToLocations = createBulkObjectFromArrayArgs(
                    (array) $this->simpleXml->ExcludeShipToLocation,
                    Location::class
                );
            }
        }

        return $this->excludeShipToLocations;
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
     * @return float|null
     */
    public function getCashOnDeliveryCost(): ?float
    {
        if ($this->cashOnDeliveryCost === null) {
            if (!empty($this->simpleXml->CODCost)) {
                $this->cashOnDeliveryCost = (float) $this->CODCost;
            }
        }

        return $this->cashOnDeliveryCost;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'cashOnDeliveryCost' => $this->getCashOnDeliveryCost(),
            'excludeShipToLocation' => (function() {
                if (!is_array($this->getExcludeShipToLocations())) {
                    return null;
                }

                return apply_on_iterable($this->getExcludeShipToLocations(), function(Location $item) {
                    return $item->toArray();
                });
            })(),
            'salesTax' => ($this->getSalesTax() instanceof SalesTax) ? $this->getSalesTax()->toArray() : null,
            'internationalShippingServiceOption' => ($this->getInternationalShippingServiceOption() instanceof InternationalShippingServiceOption) ?
                $this->getInternationalShippingServiceOption()->toArray() :
                null,
        ];
    }
}