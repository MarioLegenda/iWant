<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\BasePrice;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary;
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
     * @return array
     */
    public function toArray(): array
    {
        $toArray = [];

        $toArray['response'] = [
            'rootItem' => $this->getRoot()->toArray(),
            'shippingCostsSummary' => $this->getShippingCostsSummary()->toArray(),
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                $this->getErrors()->toArray() :
                null,
        ];

        return $toArray;
    }
}