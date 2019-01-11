<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

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
     * @param string $currency
     * @param float $price
     */
    public function __construct(
        string $currency,
        float $price
    ) {
        $this->currency = $currency;
        $this->price = $price;
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
        return [
            'currency' => $this->getCurrency(),
            'price' => $this->getPrice(),
        ];
    }
}