<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

class ShippingCostSummary
{
    /**
     * @var ListedShippingServiceCost $listedShippingServiceCost
     */
    private $listedShippingServiceCost;
    /**
     * @var ShippingServiceCost $shippingServiceCost
     */
    private $shippingServiceCost;
    /**
     * @var string $shippingServiceName
     */
    private $shippingServiceName;
    /**
     * @var string $shippingType
     */
    private $shippingType;
    /**
     * @return ListedShippingServiceCost
     */
    public function getListedShippingServiceCost(): ListedShippingServiceCost
    {
        return $this->listedShippingServiceCost;
    }
    /**
     * @return ShippingServiceCost
     */
    public function getShippingServiceCost(): ShippingServiceCost
    {
        return $this->shippingServiceCost;
    }
    /**
     * @return string
     */
    public function getShippingServiceName(): string
    {
        return $this->shippingServiceName;
    }
    /**
     * @return string
     */
    public function getShippingType(): string
    {
        return $this->shippingType;
    }
}