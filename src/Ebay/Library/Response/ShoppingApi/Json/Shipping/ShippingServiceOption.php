<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\Shipping;

use App\Ebay\Library\Response\Library\Reusability\PriceTrait;
use App\Ebay\Library\Response\ShoppingApi\Json\BasePrice;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ShippingServiceOption implements ArrayNotationInterface
{
    use PriceTrait;
    /**
     * @var string|null
     */
    private $estimatedDeliveryMaxTime;
    /**
     * @var string|null
     */
    private $estimatedDeliveryMinTime;
    /**
     * @var bool|null
     */
    private $expeditedService;
    /**
     * @var bool|null
     */
    private $fastAndFree;
    /**
     * @var string|null
     */
    private $logisticPlanType;
    /**
     * @var array|null
     */
    private $shippingInsuranceCost;
    /**
     * @var array|null
     */
    private $shippingServiceAdditionalCost;
    /**
     * @var array|null
     */
    private $shippingServiceCost;
    /**
     * @var string|null
     */
    private $shippingServiceCutOffTime;
    /**
     * @var string|null
     */
    private $shippingServiceName;
    /**
     * @var integer|null
     */
    private $shippingServicePriority;
    /**
     * @var array|null
     */
    private $shippingServiceSurCharge;
    /**
     * @var integer|null
     */
    private $shippingTimeMax;
    /**
     * @var int|null
     */
    private $shippingTimeMin;
    /**
     * @var array|null
     */
    private $shipsTo;

    /**
     * ShippingServiceOption constructor.
     * @param string|null $estimatedDeliveryMaxTime
     * @param string|null $estimatedDeliveryMinTime
     * @param bool|null $expeditedService
     * @param bool|null $fastAndFree
     * @param string|null $logisticPlanType
     * @param array|null $shippingInsuranceCost
     * @param array|null $shippingServiceAdditionalCost
     * @param array|null $shippingServiceCost
     * @param string|null $shippingServiceCutOffTime
     * @param string|null $shippingServiceName
     * @param int|null $shippingServicePriority
     * @param array|null $shippingServiceSurCharge
     * @param int|null $shippingTimeMax
     * @param int|null $shippingTimeMin
     * @param array|null $shipsTo
     */
    public function __construct(
        ?string $estimatedDeliveryMaxTime,
        ?string $estimatedDeliveryMinTime,
        ?bool $expeditedService,
        ?bool $fastAndFree,
        ?string $logisticPlanType,
        ?array $shippingInsuranceCost,
        ?array $shippingServiceAdditionalCost,
        ?array $shippingServiceCost,
        ?string $shippingServiceCutOffTime,
        ?string $shippingServiceName,
        ?int $shippingServicePriority,
        ?array $shippingServiceSurCharge,
        ?int $shippingTimeMax,
        ?int $shippingTimeMin,
        ?array $shipsTo
    ) {
        $this->estimatedDeliveryMaxTime = $estimatedDeliveryMaxTime;
        $this->estimatedDeliveryMinTime = $estimatedDeliveryMinTime;
        $this->expeditedService = $expeditedService;
        $this->fastAndFree = $fastAndFree;
        $this->logisticPlanType = $logisticPlanType;
        $this->shippingInsuranceCost = $shippingInsuranceCost;
        $this->shippingServiceAdditionalCost = $shippingServiceAdditionalCost;
        $this->shippingServiceCost = $shippingServiceCost;
        $this->shippingServiceCutOffTime = $shippingServiceCutOffTime;
        $this->shippingServiceName = $shippingServiceName;
        $this->shippingServicePriority = $shippingServicePriority;
        $this->shippingServiceSurCharge = $shippingServiceSurCharge;
        $this->shippingTimeMax = $shippingTimeMax;
        $this->shippingTimeMin = $shippingTimeMin;
        $this->shipsTo = $shipsTo;
    }
    /**
     * @return string|null
     */
    public function getEstimatedDeliveryMaxTime(): ?string
    {
        return $this->estimatedDeliveryMaxTime;
    }
    /**
     * @return string|null
     */
    public function getEstimatedDeliveryMinTime(): ?string
    {
        return $this->estimatedDeliveryMinTime;
    }
    /**
     * @return bool|null
     */
    public function getExpeditedService(): ?bool
    {
        return $this->expeditedService;
    }
    /**
     * @return bool|null
     */
    public function getFastAndFree(): ?bool
    {
        return $this->fastAndFree;
    }
    /**
     * @return string|null
     */
    public function getLogisticPlanType(): ?string
    {
        return $this->logisticPlanType;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingInsuranceCost(): ?BasePrice
    {
        return $this->insurePrice('shippingInsuranceCost');
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingServiceAdditionalCost(): ?BasePrice
    {
        return $this->insurePrice('shippingServiceAdditionalCost');
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingServiceCost(): ?BasePrice
    {
        return $this->insurePrice('shippingServiceCost');
    }
    /**
     * @return string|null
     */
    public function getShippingServiceCutOffTime(): ?string
    {
        return $this->shippingServiceCutOffTime;
    }
    /**
     * @return string|null
     */
    public function getShippingServiceName(): ?string
    {
        return $this->shippingServiceName;
    }
    /**
     * @return int|null
     */
    public function getShippingServicePriority(): ?int
    {
        return $this->shippingServicePriority;
    }
    /**
     * @return BasePrice|null
     */
    public function getShippingServiceSurCharge(): ?BasePrice
    {
        return $this->insurePrice('shippingServiceSurCharge');
    }
    /**
     * @return int|null
     */
    public function getShippingTimeMax(): ?int
    {
        return $this->shippingTimeMax;
    }
    /**
     * @return int|null
     */
    public function getShippingTimeMin(): ?int
    {
        return $this->shippingTimeMin;
    }
    /**
     * @return array|null
     */
    public function getShipsTo(): ?array
    {
        return $this->shipsTo;
    }

    public function toArray(): iterable
    {
        return [
            'estimatedDeliveryMaxTime' => $this->getEstimatedDeliveryMaxTime(),
            'estimatedDeliveryMinTime' => $this->getEstimatedDeliveryMinTime(),
            'expeditedService' => $this->getExpeditedService(),
            'fastAndFree' => $this->getFastAndFree(),
            'logisticPlanType' => $this->getLogisticPlanType(),
            'shippingInsuranceCost' => ($this->getShippingInsuranceCost() instanceof BasePrice) ? $this->getShippingInsuranceCost()->toArray() : null,
            'shippingServiceAdditionalCost' => ($this->getShippingServiceAdditionalCost() instanceof BasePrice) ? $this->getShippingServiceAdditionalCost()->toArray() : null,
            'shippingServiceCost' => ($this->getShippingServiceCost() instanceof BasePrice) ? $this->getShippingServiceCost()->toArray() : null,
            'shippingServiceCutOffTime' => $this->getShippingServiceCutOffTime(),
            'shippingServiceName' => $this->getShippingServiceName(),
            'shippingServicePriority' => $this->getShippingServicePriority(),
            'shippingServiceSurCharge' => ($this->getShippingServiceSurCharge() instanceof BasePrice) ? $this->getShippingServiceSurCharge()->toArray(): null,
            'shippingTimeMax' => $this->getShippingTimeMax(),
            'shippingTimeMin' => $this->getShippingTimeMin(),
            'shipsTo' => $this->getShipsTo(),
        ];
    }
}