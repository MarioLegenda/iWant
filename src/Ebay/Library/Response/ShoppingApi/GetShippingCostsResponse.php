<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\Json\Root;
use App\Ebay\Library\Response\ShoppingApi\Json\Shipping\ShippingSummary;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\ShoppingApi\Json\Shipping\ShippingDetails;

class GetShippingCostsResponse implements GetShippingCostsInterface, ArrayNotationInterface
{
    /**
     * @var array $response
     */
    private $response;
    /**
     * @var array
     */
    private $shippingSummary;
    /**
     * @var array
     */
    private $root;
    /**
     * @var array
     */
    private $shippingDetails;
    /**
     * GetShippingCostsResponse constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    /**
     * @return ShippingDetails|null
     */
    public function getShippingDetails(): ?ShippingDetails
    {
        if ($this->shippingDetails instanceof ShippingDetails) {
            return $this->shippingDetails;
        }

        if (!isset($this->response['ShippingDetails'])) {
            return null;
        }

        $shippingDetails = $this->response['ShippingDetails'];

        $this->shippingDetails = new ShippingDetails(
            get_value_or_null($shippingDetails, 'ShippingRateErrorMessage'),
            get_value_or_null($shippingDetails, 'ShippingServiceOption'),
            get_value_or_null($shippingDetails, 'CODCost'),
            get_value_or_null($shippingDetails, 'ExcludeShipToLocation'),
            get_value_or_null($shippingDetails, 'InsuranceCost'),
            get_value_or_null($shippingDetails, 'InsuranceOption'),
            get_value_or_null($shippingDetails, 'InternationalInsuranceCost'),
            get_value_or_null($shippingDetails, 'InternationalInsuranceOption'),
            get_value_or_null($shippingDetails, 'InternationalShippingServiceOption')
        );

        return $this->shippingDetails;
    }
    /**
     * @return ShippingSummary|null
     */
    public function getShippingCostsSummary(): ?ShippingSummary
    {
        if ($this->shippingSummary instanceof ShippingSummary) {
            return $this->shippingSummary;
        }

        $shippingSummary = $this->response['ShippingCostSummary'];

        $this->shippingSummary = new ShippingSummary(
            $shippingSummary['ShippingServiceName'],
            $shippingSummary['ShippingServiceCost'],
            $shippingSummary['ShippingType'],
            $shippingSummary['InsuranceOption'],
            $shippingSummary['ListedShippingServiceCost']
        );

        unset($shippingSummary);
        unset($this->response['ShippingCostSummary']);

        return $this->shippingSummary;
    }
    /**
     * @return bool
     */
    public function isEligibleForPickupInStore(): ?bool
    {
        if (!isset($this->response['PickUpInStoreDetails'])) {
            return null;
        }

        stringToBool($this->response['PickUpInStoreDetails']['EligibleForPickupInStore']);
    }
    /**
     * @return ArrayNotationInterface|void
     */
    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }
    /**
     * @return Root
     */
    public function getRoot(): Root
    {
        if ($this->root instanceof Root) {
            return $this->root;
        }

        $this->root = new Root(
            $this->response['Ack'],
            $this->response['Timestamp'],
            $this->response['Version']
        );

        unset($this->response['Ack']);
        unset($this->response['Timestamp']);
        unset($this->response['Version']);

        return $this->root;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'root' => $this->getRoot()->toArray(),
            'shippingSummary' => $this->getShippingCostsSummary()->toArray(),
            'shippingDetails' => ($this->getShippingDetails() instanceof ShippingDetails) ? $this->getShippingDetails()->toArray() : null,
        ];
    }
}