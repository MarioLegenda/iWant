<?php

namespace App\Component\Search\Etsy\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Price implements ArrayNotationInterface
{
    /**
     * @var string $currency
     */
    private $currency;
    /**
     * @var string $price
     */
    private $price;
    /**
     * Price constructor.
     * @param string $currency
     * @param string $price
     */
    public function __construct(
        string $currency,
        string $price
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
     * @return string
     */
    public function getPrice(): string
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