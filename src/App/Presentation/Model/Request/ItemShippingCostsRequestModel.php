<?php

namespace App\App\Presentation\Model\Request;

class ItemShippingCostsRequestModel
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var string $locale
     */
    private $locale;
    /**
     * @var string $destinationCountryCode
     */
    private $destinationCountryCode;
    /**
     * SingleItemRequestModel constructor.
     * @param string $itemId
     * @param string $locale
     * @param string $destinationCountryCode
     */
    public function __construct(
        string $itemId,
        string $locale,
        string $destinationCountryCode
    ) {
        $this->itemId = $itemId;
        $this->locale = $locale;
        $this->destinationCountryCode = $destinationCountryCode;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    /**
     * @return string
     */
    public function getDestinationCountryCode(): string
    {
        return $this->destinationCountryCode;
    }
}