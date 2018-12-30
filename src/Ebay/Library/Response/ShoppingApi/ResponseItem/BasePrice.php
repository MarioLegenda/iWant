<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class BasePrice extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $currencyId
     */
    protected $currencyId;
    /**
     * @var string $price
     */
    protected $price;
    /**
     * @param null $default
     * @return float
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
     * @return float
     */
    public function getPrice($default = null): float
    {
        if (is_null($this->price)) {
            if (!empty($this->simpleXml)) {
                $this->price = to_float($this->simpleXml);
            }
        }

        if ($this->price === null and $default !== null) {
            return $default;
        }

        return $this->price;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'currency' => $this->getCurrencyId(),
            'price' => $this->getPrice()
        ];
    }
}