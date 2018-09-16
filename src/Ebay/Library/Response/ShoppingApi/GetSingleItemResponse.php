<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\RootItemInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\RootItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\SingleItem;

class GetSingleItemResponse implements
    ResponseModelInterface
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
        'singleItem' => null,
        'errorContainer' => null,
    );
    /**
     * Response constructor.
     * @param string $xmlString
     */
    public function __construct(string $xmlString)
    {
        $this->xmlString = $xmlString;
    }
    /**
     * @return bool
     */
    public function isErrorResponse(): bool
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        return $this->getRoot()->getAck() === 'Failure';
    }
    /**
     * @return RootItem
     */
    public function getRoot(): RootItemInterface
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['rootItem'] instanceof RootItem) {
            return $this->responseItems['rootItem'];
        }

        $this->responseItems['rootItem'] = new RootItem($this->simpleXmlBase);

        return $this->responseItems['rootItem'];
    }
    /**
     * @return SingleItem
     */
    public function getSingleItem(): SingleItem
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['singleItem'] instanceof SingleItem) {
            return $this->responseItems['singleItem'];
        }

        $this->responseItems['singleItem'] = new SingleItem($this->simpleXmlBase->Item);

        return $this->responseItems['singleItem'];
    }
    /**
     * @param mixed $default
     * @return mixed|null
     */
    public function getErrors($default = null): ?ErrorContainer
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['errorContainer'] instanceof ErrorContainer) {
            return $this->responseItems['errorContainer'];
        }

        if (!empty($this->simpleXmlBase->errorMessage)) {
            $this->responseItems['errorContainer'] = new ErrorContainer($this->simpleXmlBase->errorMessage);
        }

        if (!$this->responseItems['errorContainer'] instanceof ErrorContainer and $default !== null) {
            return $default;
        }

        return $this->responseItems['errorContainer'];
    }
    /**
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->xmlString;
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
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        $toArray['response'] = array(
            'singleItem' => $this->getSingleItem()->toArray(),
            'rootItem' => $this->getRoot()->toArray(),
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                $this->getErrors()->toArray() :
                null,
        );

        return $toArray;
    }
}