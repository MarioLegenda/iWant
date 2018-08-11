<?php

namespace App\Bonanza\Library\Response\ResponseItem;

class ListingInfo
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
    public function getByItNowPrice(): string
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
}