<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ListingInfo implements ArrayNotationInterface
{
    /**
     * @var array $response
     */
    private $response;
    /**
     * ListingInfo constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    /**
     * @return string
     */
    public function getBuyItNowPrice(): string
    {
        return $this->response['buyItNowPrice'];
    }
    /**
     * @return string
     */
    public function getListingType(): string
    {
        return $this->response['listingType'];
    }
    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->response['price'];
    }
    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->response['startTime'];
    }
    /**
     * @return string
     */
    public function getLastChangeTime(): string
    {
        return $this->response['lastChangeTime'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'buyItNowPrice' => $this->getBuyItNowPrice(),
            'listingType' => $this->getListingType(),
            'price' => $this->getPrice(),
            'startTime' => $this->getStartTime(),
            'lastChangeTime' => $this->getLastChangeTime(),
        ];
    }
}