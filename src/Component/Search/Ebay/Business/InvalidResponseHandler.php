<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\ResponseFetcher\HandledResult;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\JsonFindingApiResponseModel;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Library\Response\ResponseModelInterface;

class InvalidResponseHandler
{
    /**
     * @param ResponseModelInterface|JsonFindingApiResponseModel $response
     */
    public function handleInvalidResponse(ResponseModelInterface $response): void
    {
        if ($response->getRoot()->isFailure()) {
            throw new \RuntimeException('Is error response. Handle gracefully later');
        }

        $totalEntries = $response->getPaginationOutput()->getTotalEntries();

        if ($totalEntries === 0) {
            throw new \RuntimeException('Empty search results. Handle gracefully later');
        }
    }
}