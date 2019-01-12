<?php

namespace App\Ebay\Library\Response\FindingApi;

use App\Ebay\Library\Response\FindingApi\Json\PaginationOutput;
use App\Ebay\Library\Response\FindingApi\Json\Result\Error;
use App\Ebay\Library\Response\FindingApi\Json\Root;
use App\Ebay\Library\Response\FindingApi\Json\SearchResult;
use App\Ebay\Library\Response\RootItemInterface;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class JsonFindingApiResponseModel implements FindingApiResponseModelInterface
{
    /**
     * @var array $response
     */
    private $response;
    /**
     * @var Root $root
     */
    private $root;
    /**
     * @var PaginationOutput $paginationOutput
     */
    private $paginationOutput;
    /**
     * @var array $searchResults
     */
    private $searchResults = [];
    /**
     * @var \Generator
     */
    private $searchResultsGen;
    /**
     * @var int
     */
    private $searchResultsCount;
    /**
     * JsonFindingApiResponseModel constructor.
     * @param $type
     * @param array $response
     */
    public function __construct(
        string $type,
        array $response
    ) {
        $this->response = $response[$type][0];

        if ($this->getRoot()->isSuccess() and isset($this->response['searchResult'])) {
            $count = (int) $this->response['searchResult'][0]['@count'];

            if ($count === 0) {
                $this->searchResultsCount = $count;
                return;
            }


            $this->searchResultsGen = Util::createGenerator($this->response['searchResult'][0]['item']);
            $this->searchResultsCount = (int) $this->response['searchResult'][0]['@count'];

            unset($this->response['searchResult']);
        }

    }
    /**
     * @param null $default
     * @return mixed|void
     */
    public function getAspectHistogramContainer($default = null)
    {
        throw new \RuntimeException(sprintf(
            '%s not implemented', __METHOD__
        ));
    }
    /**
     * @param null $default
     * @return mixed|void
     */
    public function getCategoryHistogramContainer($default = null)
    {
        throw new \RuntimeException(sprintf(
            '%s not implemented', __METHOD__
        ));
    }
    /**
     * @param null $default
     * @return mixed|void
     */
    public function getConditionHistogramContainer($default = null)
    {
        throw new \RuntimeException(sprintf(
            '%s not implemented', __METHOD__
        ));
    }
    /**
     * @param null $default
     * @return mixed|void
     */
    public function getErrors($default = null)
    {
        $errorsGen = Util::createGenerator($this->response['errorMessage']);

        $errors = [];
        foreach ($errorsGen as $entry) {
            $item = $entry['item'];

            $errors[] = new Error(
                $item['error'][0]['message'][0],
                $item['error'][0]['parameter'][0]
            );
        }
    }
    /**
     * @param null $default
     * @param null|bool $returnGenerator
     * @return array|\Generator
     */
    public function getSearchResults(
        $default = null,
        bool $returnGenerator = false
    ) {
        if ($this->searchResultsCount === 0) {
            return [];
        }

        if (!empty($this->searchResults)) {
            return $this->searchResults;
        }

        if ($returnGenerator) {
            return $this->searchResultsGen;
        }

        $searchResults = [];
        foreach ($this->searchResultsGen as $entry) {
            $item = $entry['item'];

            $searchResults[] = SearchResult::createFromResponseArray($item);
        }

        unset($this->response['searchResult']);

        $this->searchResults = $searchResults;

        return $this->searchResults;
    }
    /**
     * @param null $default
     * @return PaginationOutput
     */
    public function getPaginationOutput($default = null): PaginationOutput
    {
        if ($this->paginationOutput instanceof PaginationOutput) {
            return $this->paginationOutput;
        }

        $paginationOutput = new PaginationOutput(
            (int) $this->response['paginationOutput'][0]['pageNumber'][0],
            (int) $this->response['paginationOutput'][0]['entriesPerPage'][0],
            (int) $this->response['paginationOutput'][0]['totalPages'][0],
            (int) $this->response['paginationOutput'][0]['totalEntries'][0]
        );

        unset($this->response['paginationOutput']);

        $this->paginationOutput = $paginationOutput;

        return $this->paginationOutput;
    }
    /**
     * @return Root
     */
    public function getRoot(): Root
    {
        if ($this->root instanceof Root) {
            return $this->root;
        }

        $root = new Root(
            $this->response['ack'][0],
            $this->response['timestamp'][0],
            $this->response['version'][0],
            (isset($this->response['itemSearchURL'])) ? $this->response['itemSearchURL'][0] : null
        );

        unset($this->response['ack']);
        unset($this->response['timestamp']);
        unset($this->response['version']);
        unset($this->response['itemSearchURL']);

        $this->root = $root;

        return $this->root;
    }

    public function __destruct()
    {
        unset($this->response);
    }
}