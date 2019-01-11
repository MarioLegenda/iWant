<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class BasePrice implements ArrayNotationInterface
{
    /**
     * @var string
     */
    private $currency;
    /**
     * @var float
     */
    private $price;
    /**
     * BasePrice constructor.
     * @param array $priceData
     */
    public function __construct(array $priceData)
    {
        $this->currency = $priceData['@currencyId'];
        $this->price = to_float($priceData['__value__']);
    }
    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return (array) $this;
    }
}