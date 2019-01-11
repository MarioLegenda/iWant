<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\Shipping;

use App\Ebay\Library\Response\Library\Reusability\PriceTrait;
use App\Ebay\Library\Response\ShoppingApi\Json\BasePrice;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class InternationalShippingServiceOption implements ArrayNotationInterface
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
     * @var array|null
     */
    private $importCharge;
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
     * @var int|null
     */
    private $shippingServicePriority;
    /**
     * @var array|null $shipsTo
     */
    private $shipsTo;
    /**
     * InternationalShippingServiceOption constructor.
     * @param string|null $estimatedDeliveryMaxTime
     * @param string|null $estimatedDeliveryMinTime
     * @param array|null $importCharge
     * @param array|null $shippingInsuranceCost
     * @param array|null $shippingServiceAdditionalCost
     * @param array|null $shippingServiceCost
     * @param string $shippingServiceCutOffTime
     * @param string $shippingServiceName
     * @param int $shippingServicePriority
     * @param array|null $shipsTo
     */
    public function __construct(
        ?string $estimatedDeliveryMaxTime,
        ?string $estimatedDeliveryMinTime,
        ?array $importCharge,
        ?array $shippingInsuranceCost,
        ?array $shippingServiceAdditionalCost,
        ?array $shippingServiceCost,
        ?string $shippingServiceCutOffTime,
        ?string $shippingServiceName,
        ?int $shippingServicePriority,
        ?array $shipsTo
    ) {
        $this->estimatedDeliveryMaxTime = $estimatedDeliveryMaxTime;
        $this->estimatedDeliveryMinTime = $estimatedDeliveryMinTime;
        $this->importCharge = $importCharge;
        $this->shippingInsuranceCost = $shippingInsuranceCost;
        $this->shippingServiceAdditionalCost = $shippingServiceAdditionalCost;
        $this->shippingServiceCost = $shippingServiceCost;
        $this->shippingServiceCutOffTime = $shippingServiceCutOffTime;
        $this->shippingServiceName = $shippingServiceName;
        $this->shippingServicePriority = $shippingServicePriority;
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
     * @return \DateTime|null
     */
    public function getEstimatedDeliveryMaxTimeObject(): ?\DateTime
    {
        if (empty($this->getEstimatedDeliveryMaxTime())) {
            return null;
        }

        return Util::toDateTime($this->getEstimatedDeliveryMaxTime());
    }
    /**
     * @return \DateTime|null
     */
    public function getEstimatedDeliveryMinTimeObject(): ?\DateTime
    {
        if (empty($this->getEstimatedDeliveryMinTime())) {
            return null;
        }

        return Util::toDateTime($this->getEstimatedDeliveryMinTime());
    }
    /**
     * @return array|null
     */
    public function getImportCharge(): ?BasePrice
    {
        return $this->insurePrice('importCharge');
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
     * @return string
     */
    public function getShippingServiceCutOffTime(): ?string
    {
        return $this->shippingServiceCutOffTime;
    }
    /**
     * @return \DateTime|null
     */
    public function getShippingServiceCutOffTimeObject(): ?\DateTime
    {
        if (empty($this->shippingServiceCutOffTime)) {
            return null;
        }

        return Util::toDateTime($this->shippingServiceCutOffTime);
    }
    /**
     * @return string
     */
    public function getShippingServiceName(): ?string
    {
        return $this->shippingServiceName;
    }
    /**
     * @return int
     */
    public function getShippingServicePriority(): ?int
    {
        return $this->shippingServicePriority;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'estimatedDeliveryMaxTime' => Util::formatFromDate($this->getEstimatedDeliveryMaxTimeObject()),
            'estimatedDeliveryMinTime' => Util::formatFromDate($this->getEstimatedDeliveryMinTimeObject()),
            'importCharge' => ($this->getImportCharge() instanceof BasePrice) ? $this->getImportCharge()->toArray() : null,
            'shippingInsuranceCost' => ($this->getShippingInsuranceCost() instanceof BasePrice) ? $this->getShippingInsuranceCost()->toArray() : null,
            'shippingServiceAdditionalCost' => ($this->getShippingServiceAdditionalCost() instanceof BasePrice) ? $this->getShippingServiceAdditionalCost()->toArray() : null,
            'shippingServiceCost' => ($this->getShippingServiceCost() instanceof BasePrice) ? $this->getShippingServiceCost()->toArray() : null,
            'shippingServiceCutOffTime' => $this->getShippingServiceCutOffTime(),
            'shippingServiceName' => $this->getShippingServiceName(),
            'shippingServicePriority' => $this->getShippingServicePriority(),
        ];
    }
}