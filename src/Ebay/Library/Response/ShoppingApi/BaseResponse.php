<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\RootItemInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\RootItem;

class BaseResponse implements ResponseModelInterface
{
    /**
     * @var string $xmlString
     */
    protected $xmlString;
    /**
     * @var \SimpleXMLElement $simpleXmlBase
     */
    protected $simpleXmlBase;
    /**
     * @var array $responseItems
     */
    protected $responseItems = array(
        'rootItem' => null,
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
    protected function lazyLoadSimpleXml(string $xmlString)
    {
        if ($this->simpleXmlBase instanceof \SimpleXMLElement) {
            return;
        }

        $this->simpleXmlBase = simplexml_load_string($xmlString);
    }
}