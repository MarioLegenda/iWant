<?php

namespace App\Bonanza\Library\Response\ResponseItem;

class ShippingInfo
{
    /**
     * @var iterable $response
     */
    private $response;
    /**
     * ShippingInfo constructor.
     * @param iterable $response
     */
    public function __construct(iterable $response)
    {
        $this->response = $response;
    }

    public function getShippingServiceCost(): float
    {
        return (float) $this->response['shippingServiceCost'];
    }
    /**
     * @return iterable
     */
    public function getShipToLocations(): iterable
    {
        $locations = $this->response['shipToLocations'];

        if (empty($locations)) {
            return [];
        }

        if (!empty($locations) and is_array($locations)) {
            return $locations;
        }
    }
}