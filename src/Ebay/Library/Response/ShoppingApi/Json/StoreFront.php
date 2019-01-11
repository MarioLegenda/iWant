<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

class StoreFront
{
    /**
     * @var string
     */
    private $storeName;
    /**
     * @var string
     */
    private $storeUrl;
    /**
     * StoreFront constructor.
     * @param string $storeName
     * @param string $storeUrl
     */
    public function __construct(string $storeName, string $storeUrl)
    {
        $this->storeName = $storeName;
        $this->storeUrl = $storeUrl;
    }
    /**
     * @return string
     */
    public function getStoreName(): string
    {
        return $this->storeName;
    }
    /**
     * @return string
     */
    public function getStoreUrl(): string
    {
        return $this->storeUrl;
    }
}