<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SalesTax extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var BasePrice|null $salesTaxAmount
     */
    private $salesTaxAmount;
    /**
     * @var float|null $salesTaxPercent
     */
    private $salesTaxPercent;
    /**
     * @var string|null $salesTaxState
     */
    private $salesTaxState;
    /**
     * @var bool|null $shippingIncludedInTax
     */
    private $shippingIncludedInTax;
    /**
     * @return BasePrice
     */
    public function getSalesTaxAmount(): ?BasePrice
    {
        if ($this->salesTaxAmount === null) {
            if (!empty($this->simpleXml->SalesTaxAmount)) {
                $this->salesTaxAmount = new BasePrice($this->simpleXml->SalesTaxAmount);
            }
        }

        return $this->salesTaxAmount;
    }

    /**
     * @return float|null
     */
    public function getSalesTaxPercent(): ?float
    {
        if ($this->salesTaxPercent === null) {
            if (!empty($this->simpleXml->SalesTaxPercent)) {
                $this->salesTaxPercent = (float) $this->simpleXml->SalesTaxPercent;
            }
        }

        return $this->salesTaxPercent;
    }
    /**
     * @return string|null
     */
    public function getSalesTaxState(): ?string
    {
        if ($this->salesTaxState === null) {
            if (!empty($this->simpleXml->SalesTaxState)) {
                $this->salesTaxState = $this->simpleXml->SalesTaxState;
            }
        }

        return $this->salesTaxState;
    }

    /**
     * @return bool|null
     */
    public function getShippingIncludedInTax(): ?bool
    {
        if ($this->shippingIncludedInTax === null) {
            if (!empty($this->simpleXml->ShippingIncludedInTax)) {
                $this->shippingIncludedInTax = stringToBool($this->simpleXml->ShippingIncludedInTax);
            }
        }

        return $this->shippingIncludedInTax;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'salesTaxAmount' => $this->getSalesTaxAmount(),
            'salesTaxPercent' => $this->getSalesTaxPercent(),
            'salesTaxState' => $this->getSalesTaxState(),
            'shippingIncludedInTax' => $this->getShippingIncludedInTax(),
        ];
    }
}