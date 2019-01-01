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
     * @var InternationalShippingServiceOption[]|null|iterable $internationalShippingServiceOption
     */
    private $internationalShippingServiceOption;
    /**
     * @var SalesTax $salesTax
     */
    private $salesTax;
    /**
     * @var string|null
     */
    private $shippingRateErrorMessage;
    /**
     * @var ShippingServiceOption[]|iterable|null
     */
    private $shippingServiceOption;
    /**
     * @var TaxTable|null $taxTable
     */
    private $taxTable;
    /**
     * @return TaxTable|null
     */
    public function getTaxTable(): ?TaxTable
    {
        if ($this->taxTable === null) {
            if (!empty($this->simpleXml->TaxTable)) {
                $this->taxTable = new TaxTable($this->simpleXml->TaxTable);
            }
        }

        return $this->taxTable;
    }
    /**
     * @return ShippingServiceOption[]|iterable|null
     */
    public function getShippingServiceOption(): ?iterable
    {
        if ($this->shippingServiceOption === null) {
            if (!empty($this->simpleXml->ShippingServiceOption)) {
                $shippingServiceOption = $this->simpleXml->ShippingServiceOption;

                if ($shippingServiceOption->count() === 1) {
                    $this->shippingServiceOption[] = new ShippingServiceOption($this->simpleXml->ShippingServiceOption);
                } else if ($shippingServiceOption->count() > 1) {
                    foreach ($shippingServiceOption as $option) {
                        $this->shippingServiceOption[] = new ShippingServiceOption($option);
                    }
                }
            }
        }

        return $this->shippingServiceOption;
    }
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
     * @return InternationalShippingServiceOption[]|iterable|null
     */
    public function getInternationalShippingServiceOption(): ?iterable
    {
        if ($this->internationalShippingServiceOption === null) {
            $internationalShippingServiceOptions = $this->simpleXml->InternationalShippingServiceOption;

            if (!empty($this->simpleXml->InternationalShippingServiceOption)) {
                if ($internationalShippingServiceOptions->count() === 1) {
                    $this->internationalShippingServiceOption[] = new InternationalShippingServiceOption($this->simpleXml->InternationalShippingServiceOption);
                } else if ($internationalShippingServiceOptions->count() > 1) {
                    foreach ($internationalShippingServiceOptions as $option)  {
                        $this->internationalShippingServiceOption[] = new InternationalShippingServiceOption($option);
                    }
                }
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
                $this->cashOnDeliveryCost = (float) $this->simpleXml->CODCost;
            }
        }

        return $this->cashOnDeliveryCost;
    }
    /**
     * @return string|null
     */
    public function getShippingRateErrorMessage(): ?string
    {
        if ($this->shippingRateErrorMessage === null) {
            if (!empty($this->simpleXml->ShippingRateErrorMessage)) {
                $this->shippingRateErrorMessage = (float) $this->simpleXml->ShippingRateErrorMessage;
            }
        }

        return $this->shippingRateErrorMessage;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        $excludeShipToLocation = (function() {
            if (!is_array($this->getExcludeShipToLocations())) {
                return null;
            }

            return apply_on_iterable($this->getExcludeShipToLocations(), function(Location $item) {
                return $item->toArray();
            });
        })();

        $shippingServiceOption = (function() {
            if (!is_array($this->getShippingServiceOption())) {
                return null;
            }

            return apply_on_iterable($this->getShippingServiceOption(), function(ShippingServiceOption $item) {
                return $item->toArray();
            });
        })();

        $internationalShippingServiceOption = (function() {
            if (!is_array($this->getInternationalShippingServiceOption())) {
                return null;
            }

            return apply_on_iterable($this->getInternationalShippingServiceOption(), function(InternationalShippingServiceOption $item) {
                return $item->toArray();
            });
        })();

        return [
            'cashOnDeliveryCost' => $this->getCashOnDeliveryCost(),
            'insuranceCost' => ($this->getInsuranceCost() instanceof BasePrice) ? $this->getInsuranceCost()->toArray() : null,
            'insuranceOption' => ($this->getInsuranceOption() instanceof BasePrice) ? (string) $this->getInsuranceOption() : null,
            'excludeShipToLocation' => $excludeShipToLocation,
            'taxTable' => ($this->getTaxTable() instanceof TaxTable) ? $this->getTaxTable()->toArray() : null,
            'shippingServiceOption' => $shippingServiceOption,
            'shippingRateErrorMessage' => $this->getShippingRateErrorMessage(),
            'salesTax' => ($this->getSalesTax() instanceof SalesTax) ? $this->getSalesTax()->toArray() : null,
            'internationalShippingServiceOption' => $internationalShippingServiceOption,
        ];
    }
}