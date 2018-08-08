<?php

namespace App\Amazon\Library\Response\ProductAdvertisingApi;

use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\Items;
use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\OperationRequest\OperationRequestItem;
use App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem\RootItem;

class ProductAdvertisingApiResponse implements ProductAdvertisingApiResponseInterface
{
    /**
     * @var string $xmlString
     */
    private $xmlString;
    /**
     * @var \SimpleXMLElement $simpleXmlBase
     */
    private $simpleXmlBase;
    /**
     * @var array $responseItems
     */
    private $responseItems = array(
        'rootItem' => null,
        'operationRequest' => null,
        'items' => null,
    );
    /**
     * ProductAdvertisingApiResponse constructor.
     * @param string $xmlString
     */
    public function __construct(string $xmlString)
    {
        $this->xmlString = $xmlString;
    }
    /**
     * @return RootItem
     */
    public function getRoot(): RootItem
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['rootItem'] instanceof RootItem) {
            return $this->responseItems['rootItem'];
        }

        $this->responseItems['rootItem'] = new RootItem($this->simpleXmlBase);

        return $this->responseItems['rootItem'];
    }
    /**
     * @return OperationRequestItem
     */
    public function getOperationRequestItem(): OperationRequestItem
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['operationRequest'] instanceof OperationRequestItem) {
            return $this->responseItems['operationRequest'];
        }

        $this->responseItems['operationRequest'] = new OperationRequestItem($this->simpleXmlBase);

        return $this->responseItems['operationRequest'];
    }
    /**
     * @return Items
     */
    public function getItems(): Items
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['items'] instanceof Items) {
            return $this->responseItems['items'];
        }

        $this->responseItems['items'] = new Items($this->simpleXmlBase);

        return $this->responseItems['items'];
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'response' => []
        ];
    }
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    /**
     * @param string $xmlString
     */
    private function lazyLoadSimpleXml(string $xmlString)
    {
        if ($this->simpleXmlBase instanceof \SimpleXMLElement) {
            return;
        }

        $this->simpleXmlBase = simplexml_load_string($xmlString);
    }
}