<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\RootItemInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Categories;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\CategoryRootItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;

class GetCategoryInfoResponse implements
    ResponseModelInterface,
    GetCategoryInfoResponseInterface
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
        'categories' => null,
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
    public function isErrorResponse() : bool
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        return $this->getRoot()->getAck() === 'Failure';
    }
    /**
     * @return Categories
     */
    public function getCategories(): Categories
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['categories'] instanceof Categories) {
            return $this->responseItems['categories'];
        }

        $this->responseItems['categories'] = new Categories($this->simpleXmlBase);

        return $this->responseItems['categories'];
    }
    /**
     * @return RootItemInterface
     */
    public function getRoot() : RootItemInterface
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['rootItem'] instanceof CategoryRootItem) {
            return $this->responseItems['rootItem'];
        }

        $this->responseItems['rootItem'] = new CategoryRootItem($this->simpleXmlBase);

        return $this->responseItems['rootItem'];
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
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        $toArray['response'] = array(
            'categories' => $this->getCategories()->toArray(),
            'rootItem' => $this->getRoot()->toArray(),
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                $this->getErrors()->toArray() :
                null,
        );

        return $toArray;
    }
}