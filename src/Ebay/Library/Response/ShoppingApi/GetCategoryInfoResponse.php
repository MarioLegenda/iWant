<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\RootItemInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Categories;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\CategoryRootItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class GetCategoryInfoResponse extends BaseResponse implements
    GetCategoryInfoResponseInterface,
    ArrayNotationInterface
{
    /**
     * GetCategoryInfoResponse constructor.
     * @param string $xmlString
     */
    public function __construct(string $xmlString)
    {
        parent::__construct($xmlString);

        $this->responseItems['categories'] = null;
    }
    /**
     * @return CategoryRootItem
     */
    public function getRoot(): RootItemInterface
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['rootItem'] instanceof CategoryRootItem) {
            return $this->responseItems['rootItem'];
        }

        $this->responseItems['rootItem'] = new CategoryRootItem($this->simpleXmlBase);

        return $this->responseItems['rootItem'];
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
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
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