<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class DiscountPriceInfo extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var array $originalRetailPrice
     */
    private $originalRetailPrice;
    /**
     * @var string $minimumAdvertisedPriceExposure
     */
    private $minimumAdvertisedPriceExposure;
    /**
     * @var mixed $pricingTreatment
     */
    private $pricingTreatment;
    /**
     * @var bool $soldOffEbay
     */
    private $soldOffEbay;
    /**
     * @var bool $soldOnEbay
     */
    private $soldOnEbay;
    /**
     * @param null $default
     * @return array|null
     */
    public function getOriginalRetailPrice($default = null)
    {
        if ($this->originalRetailPrice === null) {
            if (!empty($this->simpleXml->originalRetailPrice)) {
                $this->setOriginalRetailPrice((string) $this->simpleXml->originalRetailPrice['currencyId'], (float) $this->simpleXml->originalRetailPrice);
            }
        }

        if ($this->originalRetailPrice === null and $default !== null) {
            return $default;
        }

        return $this->originalRetailPrice;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getMinimumAdvertisedPriceExposure($default = null)
    {
        if ($this->minimumAdvertisedPriceExposure === null) {
            if (!empty($this->simpleXml->minimumAdvertisedPriceExposure)) {
                $this->setMinimumAdvertisedPriceExposure((string) $this->simpleXml->minimumAdvertisedPriceExposure);
            }
        }

        if ($this->minimumAdvertisedPriceExposure === null and $default !== null) {
            return $default;
        }

        return $this->minimumAdvertisedPriceExposure;
    }
    /**
     * @param null $default
     * @return mixed|null
     */
    public function getPricingTreatment($default = null)
    {
        if ($this->pricingTreatment === null) {
            if (!empty($this->simpleXml->pricingTreatment)) {
                $this->setPricingTreatment((string) $this->simpleXml->pricingTreatment);
            }
        }

        if ($this->pricingTreatment === null and $default !== null) {
            return $default;
        }

        return $this->pricingTreatment;
    }
    /**
     * @param null $default
     * @return bool|null
     */
    public function getSoldOffEbay($default = null)
    {
        if ($this->soldOffEbay === null) {
            if (!empty($this->simpleXml->soldOffEbay)) {
                $this->setSoldOffEbay((bool) $this->simpleXml->soldOffEbay);
            }
        }

        if ($this->soldOffEbay === null and $default !== null) {
            return $default;
        }

        return $this->soldOffEbay;
    }
    /**
     * @param null $default
     * @return bool|null
     */
    public function getSoldOnEbay($default = null)
    {
        if ($this->soldOnEbay === null) {
            if (!empty($this->simpleXml->soldOnEbay)) {
                $this->setSoldOnEbay((bool) $this->simpleXml->soldOnEbay);
            }
        }

        if ($this->soldOnEbay === null and $default !== null) {
            return $default;
        }

        return $this->soldOnEbay;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'originalRetailPrice' => $this->getOriginalRetailPrice(),
            'pricingTreatment' => $this->getPricingTreatment(),
            'soldOffEbay' => $this->getSoldOffEbay(),
            'soldOnEbay' => $this->getSoldOnEbay(),
            'minimumAdvertisedPriceExposure' => $this->getMinimumAdvertisedPriceExposure(),
        );
    }

    private function setMinimumAdvertisedPriceExposure(string $minimumAdvertisedPriceExposure) : DiscountPriceInfo
    {
        $this->minimumAdvertisedPriceExposure = $minimumAdvertisedPriceExposure;

        return $this;
    }

    private function setOriginalRetailPrice(string $currencyId, float $amount) : DiscountPriceInfo
    {
        $this->originalRetailPrice = array(
            'currencyId' => $currencyId,
            'amount' => $amount,
        );

        return $this;
    }

    private function setPricingTreatment($pricingTreatment) : DiscountPriceInfo
    {
        $this->pricingTreatment = $pricingTreatment;

        return $this;
    }

    private function setSoldOffEbay(bool $soldOffEbay) : DiscountPriceInfo
    {
        $this->soldOffEbay = $soldOffEbay;

        return $this;
    }

    private function setSoldOnEbay(bool $soldOnEbay) : DiscountPriceInfo
    {
        $this->soldOnEbay = $soldOnEbay;

        return $this;
    }
}