<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\Shipping;

use App\Ebay\Library\Response\Library\Reusability\PriceTrait;
use App\Ebay\Library\Response\ShoppingApi\Json\BasePrice;
use App\Ebay\Library\Response\ShoppingApi\Json\Shipping\Type\InsuranceOption;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;

class ShippingDetails implements ArrayNotationInterface
{
    use PriceTrait;
    /**
     * @var string
     */
    private $shippingRateErrorMessage;
    /**
     * @var array|null
     */
    private $codCost;
    /**
     * @var array|null
     */
    private $excludeShipToLocation;
    /**
     * @var array|null
     */
    private $insuranceCost;
    /**
     * @var array|null
     */
    private $internationalInsuranceCost;
    /**
     * @var string|null
     */
    private $internationalInsuranceOption;
    /**
     * @var array|InternationalShippingServiceOption[]|null
     */
    private $internationalShippingServiceOptions;
    /**
     * @var string|null $insuranceOption
     */
    private $insuranceOption;
    /**
     * @var ShippingServiceOption|null $shippingServiceOption
     */
    private $shippingServiceOptions;
    /**
     * ShippingDetails constructor.
     * @param string|null $shippingRateErrorMessage
     * @param array|null $codCost
     * @param ShippingServiceOption[]|null $shippingServiceOptions
     * @param string|null $insuranceOption
     * @param array|null $excludeShipToLocation
     * @param array|null $insuranceCost
     * @param array|null $internationalInsuranceCost
     * @param string|null $internationalInsuranceOption
     * @param InternationalShippingServiceOption[]|array|null $internationalShippingServiceOptions
     */
    public function __construct(
        ?string $shippingRateErrorMessage,
        ?array $shippingServiceOptions,
        ?array $codCost,
        ?array $excludeShipToLocation,
        ?array $insuranceCost,
        ?string $insuranceOption,
        ?array $internationalInsuranceCost,
        ?string $internationalInsuranceOption,
        ?array $internationalShippingServiceOptions
    ) {
        $this->codCost = $codCost;
        $this->excludeShipToLocation = $excludeShipToLocation;
        $this->insuranceCost = $insuranceCost;
        $this->internationalInsuranceCost = $internationalInsuranceCost;
        $this->internationalInsuranceOption = $internationalInsuranceOption;
        $this->internationalShippingServiceOptions = $internationalShippingServiceOptions;
        $this->insuranceOption = $insuranceOption;
        $this->shippingServiceOptions = $shippingServiceOptions;
        $this->shippingRateErrorMessage = $shippingRateErrorMessage;
    }
    /**
     * @return BasePrice|null
     */
    public function getCodCost(): ?BasePrice
    {
        return $this->insurePrice('codCost');
    }
    /**
     * @return array|null
     */
    public function getExcludeShipToLocation(): ?array
    {
        return $this->excludeShipToLocation;
    }
    /**
     * @return BasePrice|null
     */
    public function getInsuranceCost(): ?BasePrice
    {
        return $this->insurePrice('insuranceCost');
    }
    /**
     * @return BasePrice|null
     */
    public function getInternationalInsuranceCost(): ?BasePrice
    {
        return $this->insurePrice('internationalInsuranceCost');
    }
    /**
     * @return TypeInterface|null
     */
    public function getInternationalInsuranceOption(): ?TypeInterface
    {
        if ($this->internationalInsuranceOption instanceof TypeInterface) {
            return $this->internationalInsuranceOption;
        }

        if (empty($this->internationalInsuranceOption)) {
            return null;
        }

        $this->internationalInsuranceOption = InsuranceOption::fromValue($this->internationalInsuranceOption);

        return $this->internationalInsuranceOption;
    }
    /**
     * @return InternationalShippingServiceOption[]|array|null
     */
    public function getInternationalShippingServiceOption(): ?array
    {
        if (!empty($this->internationalShippingServiceOptions)) {
            if ($this->internationalShippingServiceOptions[0] instanceof InternationalShippingServiceOption) {
                return $this->internationalShippingServiceOptions;
            }
        }

        if (empty($this->internationalShippingServiceOptions)) {
            return null;
        }

        $internationalShippingServiceOptions = [];
        foreach ($this->internationalShippingServiceOptions as $internationalShippingServiceOption) {
            $internationalShippingServiceOptions[] = new InternationalShippingServiceOption(
                get_value_or_null($internationalShippingServiceOption, 'EstimatedDeliveryMaxTime'),
                get_value_or_null($internationalShippingServiceOption, 'EstimatedDeliveryMinTime'),
                get_value_or_null($internationalShippingServiceOption, 'ImportCharge'),
                get_value_or_null($internationalShippingServiceOption, 'ShippingInsuranceCost'),
                get_value_or_null($internationalShippingServiceOption, 'ShippingServiceAdditionalCost'),
                get_value_or_null($internationalShippingServiceOption, 'ShippingServiceCost'),
                get_value_or_null($internationalShippingServiceOption, 'ShippingServiceCutOffTime'),
                get_value_or_null($internationalShippingServiceOption, 'ShippingServiceName'),
                get_value_or_null($internationalShippingServiceOption, 'ShippingServicePriority'),
                get_value_or_null($internationalShippingServiceOption, 'ShipsTo')
            );
        }

        $this->internationalShippingServiceOptions = $internationalShippingServiceOptions;

        return $this->internationalShippingServiceOptions;
    }
    /**
     * @return string|null
     */
    public function getInsuranceOption(): ?string
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
     * @return array|null
     */
    public function getShippingServiceOptions(): ?array
    {
        if (!empty($this->shippingServiceOptions)) {
            if ($this->shippingServiceOptions[0] instanceof ShippingServiceOption) {
                return $this->shippingServiceOptions;
            }
        }

        if (empty($this->shippingServiceOptions)) {
            return null;
        }

        $shippingServiceOptions = [];

        foreach ($this->shippingServiceOptions as $shippingServiceOption) {
            $shippingServiceOptions[] = new ShippingServiceOption(
                get_value_or_null($shippingServiceOption, 'EstimatedDeliveryMaxTime'),
                get_value_or_null($shippingServiceOption, 'EstimatedDeliveryMinTime'),
                get_value_or_null($shippingServiceOption, 'ExpeditedService'),
                get_value_or_null($shippingServiceOption, 'FastAndFree'),
                get_value_or_null($shippingServiceOption, 'LogisticPlanType'),
                get_value_or_null($shippingServiceOption, 'ShippingInsuranceCost'),
                get_value_or_null($shippingServiceOption, 'ShippingServiceAdditionalCost'),
                get_value_or_null($shippingServiceOption, 'ShippingServiceCost'),
                get_value_or_null($shippingServiceOption, 'ShippingServiceCutOffTime'),
                get_value_or_null($shippingServiceOption, 'ShippingServiceName'),
                get_value_or_null($shippingServiceOption, 'ShippingServicePriority'),
                get_value_or_null($shippingServiceOption, 'ShippingSurcharge'),
                get_value_or_null($shippingServiceOption, 'ShippingTimeMax'),
                get_value_or_null($shippingServiceOption, 'ShippingTimeMin'),
                get_value_or_null($shippingServiceOption, 'ShipsTo')
            );
        }

        $this->shippingServiceOptions = $shippingServiceOptions;

        return $shippingServiceOptions;
    }
    /**
     * @return string
     */
    public function getShippingRateErrorMessage(): string
    {
        return $this->shippingRateErrorMessage;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'codCost' => ($this->getCodCost() instanceof BasePrice) ? $this->getCodCost()->toArray() : null,
            'excludeShipToLocation' => $this->getExcludeShipToLocation(),
            'insuranceCost' => ($this->getInsuranceCost() instanceof BasePrice) ? $this->getInsuranceCost()->toArray() : null,
            'internationalInsuranceCost' => ($this->getInternationalInsuranceCost() instanceof BasePrice) ? $this->getInternationalInsuranceCost()->toArray() : null,
            'internationalInsuranceOption' => ($this->getInternationalInsuranceOption() instanceof TypeInterface) ? (string) $this->getInternationalInsuranceOption() : null,
            'internationalShippingServiceOptions' => (function() {
                if (empty($this->getInternationalShippingServiceOption())) {
                    return null;
                }

                $options = [];
                foreach ($this->internationalShippingServiceOptions as $shippingServiceOption) {
                    $options[] = $shippingServiceOption->toArray();
                }

                return $options;
            })(),
            'insuranceOption' => ($this->getInsuranceOption() instanceof TypeInterface) ? (string) $this->getInsuranceOption() : null,
            'shippingServiceOptions' => (function() {
                if (empty($this->getShippingServiceOptions())) {
                    return null;
                }

                $options = [];
                foreach ($this->shippingServiceOptions as $shippingServiceOption) {
                    $options[] = $shippingServiceOption->toArray();
                }

                return $options;
            })(),
        ];
    }
}