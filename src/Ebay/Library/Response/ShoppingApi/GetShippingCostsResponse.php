<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingDetails;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class GetShippingCostsResponse extends BaseResponse
    implements GetShippingCostsInterface, ArrayNotationInterface
{
    /**
     * GetShippingCostsResponse constructor.
     * @param string $xmlString
     */
    public function __construct(string $xmlString)
    {
        parent::__construct($xmlString);

        $this->responseItems['shippingCostsSummary'] = null;
        $this->responseItems['importCharge'] = null;
        $this->responseItems['eligibleForPickupInStore'] = null;
        $this->responseItems['shippingDetails'] = null;
    }
    /**
     * @return ShippingDetails|null
     */
    public function getShippingDetails(): ?ShippingDetails
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['shippingDetails'] instanceof ShippingDetails) {
            return $this->responseItems['shippingDetails'];
        }

        $this->responseItems['shippingDetails'] = new ShippingDetails($this->simpleXmlBase->ShippingDetails);

        return $this->responseItems['shippingDetails'];
    }
    /**
     * @return ShippingCostSummary
     */
    public function getShippingCostsSummary(): ShippingCostSummary
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['shippingCostsSummary'] instanceof ShippingCostSummary) {
            return $this->responseItems['shippingCostsSummary'];
        }

        $this->responseItems['shippingCostsSummary'] = new ShippingCostSummary($this->simpleXmlBase->ShippingCostSummary);

        return $this->responseItems['shippingCostsSummary'];
    }
    /**
     * @return bool
     */
    public function isEligibleForPickupInStore(): ?bool
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if (!is_null($this->responseItems['eligibleForPickupInStore'])) {
            if (!empty($this->simpleXmlBase->PickUpInStoreDetails)) {
                if (!empty($this->simpleXmlBase->PickUpInStoreDetails->EligibleForPickupInStore)) {
                    $this->responseItems['eligibleForPickupInStore'] = stringToBool($this->simpleXmlBase->PickUpInStoreDetails->EligibleForPickupInStore);
                }
            }
        }

        return $this->responseItems['eligibleForPickupInStore'];
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = [];

        $toArray['response'] = [
            'rootItem' => $this->getRoot()->toArray(),
            'shippingCostsSummary' => $this->getShippingCostsSummary()->toArray(),
            'shippingDetails' => $this->getShippingDetails()->toArray(),
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                $this->getErrors()->toArray() :
                null,
        ];

        return $toArray;
    }
}