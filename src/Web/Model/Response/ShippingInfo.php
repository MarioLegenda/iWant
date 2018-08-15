<?php

namespace App\Web\Model\Response;

class ShippingInfo
{
    /**
     * @var iterable $locations
     */
    private $locations;
    /**
     * @var float $shippingCost
     */
    private $shippingCost;
    /**
     * ShippingInfo constructor.
     * @param float $shippingCost
     * @param iterable $locations
     */
    public function __construct(
        float $shippingCost,
        iterable $locations
    ) {
        $this->shippingCost = $shippingCost;
        $this->locations = $locations;
    }
    /**
     * @return iterable
     */
    public function getLocations(): iterable
    {
        return $this->locations;
    }
    /**
     * @return float
     */
    public function getShippingCost(): ?float
    {
        return $this->shippingCost;
    }
}