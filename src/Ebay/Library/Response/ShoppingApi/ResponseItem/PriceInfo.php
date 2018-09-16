<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class PriceInfo implements ArrayNotationInterface
{
    /**
     * @var string|null $convertedCurrentPriceId
     */
    private $convertedCurrentPriceId;
    /**
     * @var string|null $convertedCurrentPrice
     */
    private $convertedCurrentPrice;
    /**
     * @var string|null $currentPriceId
     */
    private $currentPriceId;
    /**
     * @var string|null $currentPrice
     */
    private $currentPrice;
    /**
     * PriceInfo constructor.
     * @param ConvertedCurrentPrice|null $convertedCurrentPrice
     * @param CurrentPrice|null $currentPrice
     */
    public function __construct(
        ConvertedCurrentPrice $convertedCurrentPrice = null,
        CurrentPrice $currentPrice = null
    ) {
        if ($convertedCurrentPrice instanceof ConvertedCurrentPrice) {
            $this->convertedCurrentPriceId = $convertedCurrentPrice->getCurrencyId();
            $this->convertedCurrentPrice = $convertedCurrentPrice->getPrice();
        }

        if ($currentPrice instanceof CurrentPrice) {
            $this->currentPriceId = $currentPrice->getCurrencyId();
            $this->currentPrice = $currentPrice->getPrice();
        }
    }
    /**
     * @return null|string
     */
    public function getConvertedCurrentPriceId(): ?string
    {
        return $this->convertedCurrentPriceId;
    }
    /**
     * @return null|string
     */
    public function getConvertedCurrentPrice(): ?string
    {
        return $this->convertedCurrentPrice;
    }
    /**
     * @return null|string
     */
    public function getCurrentPriceId(): ?string
    {
        return $this->currentPriceId;
    }
    /**
     * @return null|string
     */
    public function getCurrentPrice(): ?string
    {
        return $this->currentPrice;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'convertedCurrentPriceId' => $this->getConvertedCurrentPriceId(),
            'convertedCurrentPrice' => $this->getConvertedCurrentPrice(),
            'currentPriceId' => $this->getCurrentPriceId(),
            'currentPrice' => $this->getCurrentPrice(),
        ];
    }
}