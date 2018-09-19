<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class ConvertedCurrentPrice extends AbstractItem
{
    /**
     * @var string $currencyId
     */
    private $currencyId;
    /**
     * @var string $price
     */
    private $price;
    /**
     * @param null $default
     * @return string
     */
    public function getCurrencyId($default = null): string
    {
        if (is_null($this->currencyId)) {
            if (!empty($this->simpleXml['currencyID'])) {
                $this->currencyId = (string) $this->simpleXml['currencyID'];
            }
        }

        if ($this->currencyId === null and $default !== null) {
            return $default;
        }

        return $this->currencyId;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getPrice($default = null): string
    {
        if (is_null($this->price)) {
            if (!empty($this->simpleXml)) {
                $this->price = (string) $this->simpleXml;
            }
        }

        if ($this->price === null and $default !== null) {
            return $default;
        }

        return $this->price;
    }
}