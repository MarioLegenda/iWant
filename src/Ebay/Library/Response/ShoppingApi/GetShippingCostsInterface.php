<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost\ShippingCostSummary;

interface GetShippingCostsInterface
{
    public function getShippingCostsSummary(): ShippingCostSummary;
}