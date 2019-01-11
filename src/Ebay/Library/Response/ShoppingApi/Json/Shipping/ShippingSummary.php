<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\Shipping;

use App\Ebay\Library\Response\Library\Reusability\PriceTrait;
use App\Ebay\Library\Response\ShoppingApi\Json\BasePrice;
use App\Ebay\Library\Response\ShoppingApi\Json\Shipping\Type\InsuranceOption;
use App\Ebay\Library\Response\ShoppingApi\Json\Shipping\Type\ShippingType;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;

class ShippingSummary implements ArrayNotationInterface
{
    use PriceTrait;

    /**
     * @var string
     */
    private $shippingServiceName;
    /**
     * @var array
     */
    protected $shippingServiceCost;
    /**
     * @var string
     */
    private $shippingType;
    /**
     * @var string
     */
    private $insuranceOption;
    /**
     * @var array
     */
    private $listedShippingServiceCost;
    /**
     * ShippingSummary constructor.
     * @param string $shippingServiceName
     * @param array $shippingServiceCost
     * @param string $shippingType
     * @param string $insuranceOption
     * @param array $listedShippingServiceCost
     */
    public function __construct(
        string $shippingServiceName,
        array $shippingServiceCost,
        string $shippingType,
        string $insuranceOption,
        array $listedShippingServiceCost
    ) {
        $this->shippingServiceName = $shippingServiceName;
        $this->shippingServiceCost = $shippingServiceCost;
        $this->shippingType = $shippingType;
        $this->insuranceOption = $insuranceOption;
        $this->listedShippingServiceCost = $listedShippingServiceCost;
    }
    /**
     * @return string
     */
    public function getShippingServiceName(): string
    {
        return $this->shippingServiceName;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingServiceCost(): ?BasePrice
    {
        return $this->insurePrice('shippingServiceCost');
    }
    /**
     * @return TypeInterface|null
     */
    public function getShippingType(): ?TypeInterface
    {
        if ($this->shippingType instanceof TypeInterface) {
            return $this->shippingType;
        }

        if (empty($this->shippingType)) {
            return null;
        }

        $this->shippingType = ShippingType::fromValue($this->shippingType);

        return $this->shippingType;
    }
    /**
     * @return TypeInterface|null
     */
    public function getInsuranceOption(): ?TypeInterface
    {
        if ($this->insuranceOption instanceof TypeInterface) {
            return $this->insuranceOption;
        }

        if (empty($this->insuranceOption)) {
            return null;
        }

        $this->insuranceOption = InsuranceOption::fromValue($this->insuranceOption);

        return $this->insuranceOption;
    }
    /**
     * @return BasePrice|null
     */
    public function getListedShippingServiceCost(): ?BasePrice
    {
        if ($this->listedShippingServiceCost instanceof BasePrice) {
            return $this->listedShippingServiceCost;
        }

        if (empty($this->listedShippingServiceCost)) {
            return null;
        }

        $this->listedShippingServiceCost = new BasePrice(
            $this->listedShippingServiceCost['CurrencyID'],
            $this->listedShippingServiceCost['Value']
        );

        return $this->listedShippingServiceCost;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'shippingServiceName' => $this->getShippingServiceName(),
            'shippingServiceCost' => ($this->getShippingServiceCost() instanceof BasePrice) ? $this->getShippingServiceCost()->toArray() : null,
            'shippingType' => ($this->getShippingType() instanceof TypeInterface) ? $this->getShippingType()->getValue() : null,
            'insuranceOption' => ($this->getInsuranceOption() instanceof TypeInterface) ? $this->getInsuranceOption()->getValue() : null,
            'listedShippingServiceCost' => ($this->getListedShippingServiceCost() instanceof TypeInterface) ? $this->getListedShippingServiceCost()->toArray() : null,
        ];
    }
}