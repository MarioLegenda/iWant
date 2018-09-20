<?php

namespace App\Etsy\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ShippingProfileEntryResult implements ArrayNotationInterface
{
    /**
     * @var array $resultItem
     */
    private $resultItem;
    /**
     * Result constructor.
     * @param array $resultItem
     */
    public function __construct(array $resultItem)
    {
        $this->resultItem = $resultItem;
    }
    /**
     * @return null|string
     */
    public function getShippingInfoId(): string
    {
        return $this->resultItem['shipping_info_id'];
    }
    /**
     * @return null|string
     */
    public function getOriginCountryId(): string
    {
        return $this->resultItem['origin_country_id'];
    }
    /**
     * @return null|string
     */
    public function getDestinationCountryId(): ?string
    {
        return $this->resultItem['destination_country_id'];
    }
    /**
     * @return null|string
     */
    public function getCurrencyCode(): string
    {
        return $this->resultItem['currency_code'];
    }
    /**
     * @return null|string
     */
    public function getPrimaryCost(): string
    {
        return $this->resultItem['primary_cost'];
    }
    /**
     * @return null|string
     */
    public function getSecondaryCost(): string
    {
        return $this->resultItem['secondary_cost'];
    }
    /**
     * @return null|string
     */
    public function getListingId(): string
    {
        return $this->resultItem['listing_id'];
    }
    /**
     * @return null|string
     */
    public function getRegionId(): ?string
    {
        return $this->resultItem['region_id'];
    }
    /**
     * @return null|string
     */
    public function getOriginCountryName(): string
    {
        return $this->resultItem['origin_country_name'];
    }
    /**
     * @return null|string
     */
    public function getDestinationCountryName(): string
    {
        return $this->resultItem['destination_country_name'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'shippingInfoId' => $this->getShippingInfoId(),
            'originCountryId' => $this->getOriginCountryId(),
            'destinationCountryId' => $this->getDestinationCountryId(),
            'currencyCode' => $this->getCurrencyCode(),
            'primaryCost' => $this->getPrimaryCost(),
            'secondaryCost' => $this->getSecondaryCost(),
            'listingId' => $this->getListingId(),
            'regionId' => $this->getRegionId(),
            'originCountryName' => $this->getOriginCountryName(),
            'destinationCountryName' => $this->getDestinationCountryName(),
        ];
    }
}