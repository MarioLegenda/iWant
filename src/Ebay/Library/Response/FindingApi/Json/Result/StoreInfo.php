<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class StoreInfo implements ArrayNotationInterface
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
     * StoreInfo constructor.
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
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return (array) $this;
    }
}