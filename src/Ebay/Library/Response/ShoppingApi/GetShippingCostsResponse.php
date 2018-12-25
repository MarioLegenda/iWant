<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCostSummary;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class GetShippingCostsResponse extends BaseResponse
    implements GetShippingCostsInterface, ArrayNotationInterface
{
    public function getShippingCostsSummary(): ShippingCostSummary
    {
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = [];

        $toArray['response'] = [
            'rootItem' => $this->getRoot()->toArray(),
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                $this->getErrors()->toArray() :
                null,
        ];

        return $toArray;
    }
}