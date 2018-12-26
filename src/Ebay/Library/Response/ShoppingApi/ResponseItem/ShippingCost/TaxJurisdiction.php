<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItem;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class TaxJurisdiction extends AbstractItem implements ArrayNotationInterface
{
    public function toArray(): iterable
    {

    }
}