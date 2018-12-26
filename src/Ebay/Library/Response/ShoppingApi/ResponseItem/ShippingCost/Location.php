<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Information\ISO3166CountryCodeInformation;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Location implements ArrayNotationInterface
{
    /**
     * @var string $location
     */
    private $location;
    /**
     * @var bool $isCountry
     */
    private $isCountry = false;
    /**
     * @var bool $isRegion
     */
    private $isRegion = false;
    /**
     * @var bool $isUndefined
     */
    private $isUndefined = false;
    /**
     * Location constructor.
     * @param $location
     */
    public function __construct($location)
    {
        if (ISO3166CountryCodeInformation::instance()->has($location)) {
            $this->isCountry = true;
        } else {
            $this->isUndefined = true;
        }

        $this->location = $location;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'location' => $this->location,
            'isCountry' => $this->isCountry,
            'isUndefined' => $this->isUndefined,
            'isRegion' => $this->isRegion,
        ];
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->location;
    }
}