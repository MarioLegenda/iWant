<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Information\EbayRegionInformation;
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
     * @var bool $isWorldwide
     */
    private $isWorldwide = false;
    /**
     * Location constructor.
     * @param string $location
     */
    public function __construct(string $location)
    {
        $this->determineLocationType($location);

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
            'isWorldwide' => $this->isWorldwide,
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
    /**
     * @param string $location
     */
    private function determineLocationType(string $location): void
    {
        if (ISO3166CountryCodeInformation::instance()->has($location)) {
            $this->isCountry = true;
        } else if (EbayRegionInformation::instance()->has($location)) {
            $this->isRegion = true;
        } else if ($location === 'Worldwide') {
            $this->isWorldwide = true;
        } else {
            $this->isUndefined = true;
        }
    }
}