<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ReturnPolicy;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ReturnPolicy\Type\RefundType;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ReturnPolicy\Type\ReturnType;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;

class ReturnPolicy extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var string|null $ean
     */
    private $ean;
    /**
     * @var RefundType|null $refund
     */
    private $refund;
    /**
     * @var ReturnType $returnType
     */
    private $returnType;
    /**
     * @var string $returnsWithin
     */
    private $returnsWithin;
    /**
     * @var string $shippingCostPaidBy
     */
    private $shippingCostPaidBy;
    /**
     * @var string $warrantyDuration
     */
    private $warrantyDuration;
    /**
     * @var string $warrantyOffered
     */
    private $warrantyOffered;
    /**
     * @var string $warrantyType
     */
    private $warrantyType;
    /**
     * @param null $default
     * @return null|string
     */
    public function getWarrantyType($default = null): ?string
    {
        if ($this->warrantyType=== null) {
            if (!empty($this->simpleXml->WarrantyType)) {
                $this->warrantyType = (string) $this->simpleXml->WarrantyType;
            }
        }

        if ($this->warrantyType === null and $default !== null) {
            return $default;
        }

        return $this->warrantyType;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getWarrantyOffered($default = null): ?string
    {
        if ($this->warrantyOffered=== null) {
            if (!empty($this->simpleXml->WarrantyOffered)) {
                $this->warrantyOffered = (string) $this->simpleXml->WarrantyOffered;
            }
        }

        if ($this->warrantyOffered === null and $default !== null) {
            return $default;
        }

        return $this->warrantyOffered;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getWarrantyDuration($default = null): ?string
    {
        if ($this->warrantyDuration=== null) {
            if (!empty($this->simpleXml->WarrantyDuration)) {
                $this->warrantyDuration = (string) $this->simpleXml->WarrantyDuration;
            }
        }

        if ($this->warrantyDuration === null and $default !== null) {
            return $default;
        }

        return $this->warrantyDuration;
    }
    /**
    /**
     * @param null $default
     * @return string
     */
    public function getShippingCostPaidBy($default = null): ?string
    {
        if ($this->shippingCostPaidBy === null) {
            if (!empty($this->simpleXml->ShippingCostPaidBy)) {
                $this->shippingCostPaidBy = (string) $this->simpleXml->ShippingCostPaidBy;
            }
        }

        if ($this->shippingCostPaidBy === null and $default !== null) {
            return $default;
        }

        return $this->shippingCostPaidBy;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getReturnsWithin($default = null): ?string
    {
        if ($this->returnsWithin === null) {
            if (!empty($this->simpleXml->ReturnsWithin)) {
                $this->returnsWithin = (string) $this->simpleXml->ReturnsWithin;
            }
        }

        if ($this->returnsWithin === null and $default !== null) {
            return $default;
        }

        return $this->returnsWithin;
    }
    /**
     * @param null $default
     * @return ReturnType|null
     */
    public function getReturnType($default = null): ?ReturnType
    {
        if ($this->returnType === null) {
            if (!empty($this->simpleXml->ReturnsAccepted)) {
                $returnType = (string) $this->simpleXml->ReturnsAccepted;

                try {
                    $returnType = ReturnType::fromValue($returnType);
                } catch (\Exception $e) {
                    $returnType = null;
                }

                $this->returnType = $returnType;
            }
        }

        if ($this->returnType === null and $default !== null) {
            return $default;
        }

        return $this->returnType;
    }
    /**
     * @param null $default
     * @return RefundType|null
     */
    public function getRefund($default = null): ?RefundType
    {
        if ($this->refund === null) {
            if (!empty($this->simpleXml->Refund)) {
                $refund = (string) $this->simpleXml->Description;

                try {
                    $refund = RefundType::fromValue($refund);
                } catch (\Exception $e) {
                    $refund = null;
                }

                $this->refund = $refund;
            }
        }

        if ($this->refund === null and $default !== null) {
            return $default;
        }

        return $this->refund;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getDescription($default = null): ?string
    {
        if ($this->description === null) {
            if (!empty($this->simpleXml->Description)) {
                $this->description = (string) $this->simpleXml->Description;
            }
        }

        if ($this->description === null and $default !== null) {
            return $default;
        }

        return $this->description;
    }
    /**
     * @param null $default
     * @return string|null
     */
    public function getEan($default = null): ?string
    {
        if ($this->ean === null) {
            if (!empty($this->simpleXml->EAN)) {
                $this->ean = (string) $this->simpleXml->EAN;
            }
        }

        if ($this->ean === null and $default !== null) {
            return $default;
        }

        return $this->ean;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'description' => $this->getDescription(),
            'ean' => $this->getEan(),
            'refund' => ($this->getRefund() instanceof TypeInterface) ? (string) $this->getRefund() : null,
            'returnType' => ($this->getReturnType() instanceof TypeInterface) ? (string) $this->getReturnType() : null,
            'returnsWithin' => $this->getReturnsWithin(),
            'shippingCostPaidBy' => $this->getShippingCostPaidBy(),
            'warrantyDuration' => $this->getWarrantyDuration(),
            'warrantyOffered' => $this->getWarrantyOffered(),
            'warrantyType' => $this->getWarrantyType(),
        ];
    }
}