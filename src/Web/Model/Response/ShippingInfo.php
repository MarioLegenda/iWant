<?php

namespace App\Web\Model\Response;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Web\Model\Response\Type\DeferrableType;

class ShippingInfo implements DeferrableHttpDataObjectInterface
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
        float $shippingCost = null,
        iterable $locations = null
    ) {
        $this->shippingCost = $shippingCost;
        $this->locations = $locations;
    }
    /**
     * @return TypeInterface
     */
    public function getDeferrableType(): TypeInterface
    {
        return DeferrableType::fromValue('concrete_object');
    }
    /**
     * @return iterable
     */
    public function getDeferrableData(): iterable
    {
        $message = sprintf(
            '%s already has all necessary data and does not have to be deferred therefor, %s::getDeferrableData() cannot be used',
            get_class($this),
            get_class($this)
        );

        throw new \RuntimeException($message);
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