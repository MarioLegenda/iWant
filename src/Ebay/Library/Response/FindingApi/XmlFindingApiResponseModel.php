<?php

namespace App\Ebay\Library\Response\FindingApi;

use App\Ebay\Library\Response\FindingApi\ResponseItem\AspectHistogramContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\CategoryHistogramContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\ConditionHistogramContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\PaginationOutput;
use App\Ebay\Library\Response\FindingApi\ResponseItem\RootItem;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class XmlFindingApiResponseModel implements FindingApiResponseModelInterface, ArrayNotationInterface, \JsonSerializable
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
        'aspectHistogram' => null,
        'searchResult' => null,
        'conditionHistogramContainer' => null,
        'errorContainer' => null,
        'paginationOutput' => null,
        'categoryHistogram' => null,
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
     * @return RootItem
     */
    public function getRoot() : RootItem
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
     * @return null|AspectHistogramContainer
     */
    public function getAspectHistogramContainer($default = null)
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['aspectHistogram'] instanceof AspectHistogramContainer) {
            return $this->responseItems['aspectHistogram'];
        }

        if (!empty($this->simpleXmlBase->aspectHistogramContainer)) {
            $this->responseItems['aspectHistogram'] = new AspectHistogramContainer($this->simpleXmlBase->aspectHistogramContainer);
        }

        if (!$this->responseItems['aspectHistogram'] instanceof AspectHistogramContainer and $default !== null) {
            return $default;
        }

        return $this->responseItems['aspectHistogram'];
    }
    /**
     * @return SearchResultsContainer
     */
    public function getSearchResults($default = null)
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['searchResult'] instanceof SearchResultsContainer) {
            return $this->responseItems['searchResult'];
        }

        if (!empty($this->simpleXmlBase->searchResult)) {
            $this->responseItems['searchResult'] = new SearchResultsContainer($this->simpleXmlBase->searchResult);
        }

        if ($this->responseItems['searchResult'] === null and $default !== null) {
            return $default;
        }

        return $this->responseItems['searchResult'];
    }
    /**
     * @param null $default
     * @return mixed
     */
    public function getConditionHistogramContainer($default = null)
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['conditionHistogramContainer'] instanceof ConditionHistogramContainer) {
            return $this->responseItems['conditionHistogramContainer'];
        }

        if (!empty($this->simpleXmlBase->conditionHistogramContainer)) {
            $this->responseItems['conditionHistogramContainer'] = new ConditionHistogramContainer($this->simpleXmlBase->conditionHistogramContainer);
        }

        if (!$this->responseItems['conditionHistogramContainer'] instanceof ConditionHistogramContainer and $default !== null) {
            return $default;
        }

        return $this->responseItems['conditionHistogramContainer'];
    }
    /**
     * @param null $default
     * @return mixed|null
     */
    public function getPaginationOutput($default = null)
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['paginationOutput'] instanceof PaginationOutput) {
            return $this->responseItems['paginationOutput'];
        }

        if (!empty($this->simpleXmlBase->paginationOutput)) {
            $this->responseItems['paginationOutput'] = new PaginationOutput($this->simpleXmlBase->paginationOutput);
        }

        if (!$this->responseItems['paginationOutput'] instanceof PaginationOutput and $default !== null) {
            return $default;
        }

        return $this->responseItems['paginationOutput'];
    }
    /**
     * @param null $default
     * @return mixed|null
     */
    public function getCategoryHistogramContainer($default = null)
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['categoryHistogram'] instanceof CategoryHistogramContainer) {
            return $this->responseItems['categoryHistogram'];
        }

        if (!empty($this->simpleXmlBase->categoryHistogramContainer)) {
            $this->responseItems['categoryHistogram'] = new CategoryHistogramContainer($this->simpleXmlBase->categoryHistogramContainer);
        }

        if (!$this->responseItems['categoryHistogram'] instanceof CategoryHistogramContainer and $default !== null) {
            return $default;
        }

        return $this->responseItems['categoryHistogram'];
    }
    /**
     * @param null $default
     * @return mixed|null
     */
    public function getErrors($default = null)
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
     * @return bool
     */
    public function isErrorResponse() : bool
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        return $this->getRoot()->getAck() === 'Failure';
    }
    /**
     * @return string
     */
    public function getRawResponse() : string
    {
        return $this->xmlString;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        $toArray['response'] = array(
            'rootItem' => $this->getRoot()->toArray(),
            'aspectHistogramContainer' => ($this->getAspectHistogramContainer() instanceof AspectHistogramContainer) ?
                                            $this->getAspectHistogramContainer()->toArray() :
                                            null,
            'searchResultsContainer' => ($this->getSearchResults() instanceof SearchResultsContainer) ?
                                            $this->getSearchResults()->toArray() :
                                            null,
            'conditionHistogramContainer' => ($this->getConditionHistogramContainer() instanceof ConditionHistogramContainer) ?
                                                $this->getConditionHistogramContainer()->toArray() :
                                                null,
            'paginationOutput' => ($this->getPaginationOutput() instanceof PaginationOutput) ?
                                    $this->getPaginationOutput()->toArray() :
                                    null,
            'categoryHistogramContainer' => ($this->getCategoryHistogramContainer() instanceof CategoryHistogramContainer) ?
                                                $this->getCategoryHistogramContainer()->toArray() :
                                                null,
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                            $this->getErrors()->toArray() :
                            null,
        );

        return $toArray;
    }
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    private function lazyLoadSimpleXml($xmlString)
    {
        if ($this->simpleXmlBase instanceof \SimpleXMLElement) {
            return;
        }

        $this->simpleXmlBase = simplexml_load_string($xmlString);
    }
}