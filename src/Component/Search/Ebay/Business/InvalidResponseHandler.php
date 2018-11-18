<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\ResponseFetcher\HandledResult;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Library\Response\ResponseModelInterface;

class InvalidResponseHandler
{
    /**
     * @param ResponseModelInterface|XmlFindingApiResponseModel $response
     */
    public function handleInvalidResponse(ResponseModelInterface $response): void
    {
        if ($response->isErrorResponse()) {
            throw new \RuntimeException('Is error response. Handle gracefully later');
        }

        /** @var SearchResultsContainer $searchResults */
        $searchResults = $response->getSearchResults();

        if (empty($searchResults)) {
            throw new \RuntimeException('Empty search results. Handle gracefully later');
        }
    }
}