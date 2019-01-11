<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SellingStatus implements ArrayNotationInterface
{
    /**
     * @var BasePrice
     */
    private $currentPrice;
    /**
     * @var BasePrice
     */
    private $convertedCurrentPrice;
    /**
     * @var string
     */
    private $sellingState;
    /**
     * @var string
     */
    private $timeLeft;
    /**
     * SellingStatus constructor.
     * @param BasePrice $currentPrice
     * @param BasePrice $convertedCurrentPrice
     * @param string $sellingState
     * @param string $timeLeft
     */
    public function __construct(
        BasePrice $currentPrice,
        BasePrice $convertedCurrentPrice,
        string $sellingState,
        string $timeLeft
    ) {
        $this->currentPrice = $currentPrice;
        $this->convertedCurrentPrice = $convertedCurrentPrice;
        $this->sellingState = $sellingState;
        $this->timeLeft = $timeLeft;
    }
    /**
     * @return BasePrice
     */
    public function getCurrentPrice(): BasePrice
    {
        return $this->currentPrice;
    }
    /**
     * @return BasePrice
     */
    public function getConvertedCurrentPrice(): BasePrice
    {
        return $this->convertedCurrentPrice;
    }
    /**
     * @return string
     */
    public function getSellingState(): string
    {
        return $this->sellingState;
    }
    /**
     * @return string
     */
    public function getTimeLeft(): string
    {
        return $this->timeLeft;
    }
    /**
     * @return \DateTime
     */
    public function getTimeLeftAsObject(): \DateTime
    {
        throw new \RuntimeException('Not implemented');
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'currentPrice' => $this->getCurrentPrice()->toArray(),
            'convertedCurrentPrice' => $this->getConvertedCurrentPrice()->toArray(),
            'sellingState' => $this->getSellingState(),
            'timeLeft' => $this->getTimeLeft(),
        ];
    }
}