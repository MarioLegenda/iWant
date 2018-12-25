<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCostSummary;

interface GetShippingCostsInterface
{
    public function getShippingCostsSummary(): ShippingCostSummary;
}