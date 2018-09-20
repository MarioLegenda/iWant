<?php

namespace App\Etsy\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Country implements ArrayNotationInterface
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
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->resultItem['country_id'];
    }
    /**
     * @return string
     */
    public function getIsoCountryCode(): string
    {
        return $this->resultItem['iso_country_code'];
    }
    /**
     * @return string
     */
    public function getWorldBankCountryCode(): string
    {
        return $this->resultItem['world_bank_country_code'];
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->resultItem['name'];
    }
    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->resultItem['slug'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'countryId' => $this->getCountryId(),
            'isoCountryCode' => $this->getIsoCountryCode(),
            'worldBankCountryCode' => $this->getWorldBankCountryCode(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
        ];
    }
}