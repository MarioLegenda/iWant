<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class ShippingCostSummary extends AbstractItem
{
    /**
     * @var string $shippingType
     */
    private $shippingType;
    /**
     * @param null $default
     * @return null|string
     */
    public function getShippingType($default = null)
    {
        if ($this->shippingType === null) {
            if (!empty($this->simpleXml->ShippingType)) {
                $this->shippingType = (string) $this->simpleXml->ShippingType;
            }
        }

        if ($this->shippingType === null and $default !== null) {
            return $default;
        }

        return $this->shippingType;
    }
}