<?php

namespace App\Component\Search\Ebay\Business\ResponseFetcher;

use App\Component\Search\Ebay\Business\Cache\UniqueIdentifierFactory;
use App\Component\Search\Ebay\Business\Factory\SearchResponseModelFactory;
use App\Component\Search\Ebay\Business\Finder;
use App\Component\Search\Ebay\Business\InvalidResponseHandler;
use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;

class ResponseFetcher
{
    /**
     * @var InvalidResponseHandler $invalidResponseHandler
     */
    private $invalidResponseHandler;
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * @var SearchResponseModelFactory $searchResponseModelFactory
     */
    private $searchResponseModelFactory;
    /**
     * ResponseFetcher constructor.
     * @param InvalidResponseHandler $invalidResponseHandler
     * @param Finder $finder
     * @param SearchResponseModelFactory $searchResponseModelFactory
     */
    public function __construct(
        InvalidResponseHandler $invalidResponseHandler,
        Finder $finder,
        SearchResponseModelFactory $searchResponseModelFactory
    ) {
        $this->invalidResponseHandler = $invalidResponseHandler;
        $this->finder = $finder;
        $this->searchResponseModelFactory = $searchResponseModelFactory;
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @param string|null $identifier
     * @return iterable
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    public function getResponse(SearchModelInterface $model, string $identifier = null): iterable
    {
        /** @var XmlFindingApiResponseModel $findingApiResponse */
        $findingApiResponse = $this->searchEbayAdvanced($model);

        $this->invalidResponseHandler->handleInvalidResponse($findingApiResponse);

        $searchResults = $findingApiResponse->getSearchResults();

        $identifier = (is_string($identifier)) ? $identifier : UniqueIdentifierFactory::createIdentifier($model);

        /** @var TypedArray $typedArrayResults */
        return $this->searchResponseModelFactory->fromSearchResults(
            $identifier,
            $model->getGlobalId(),
            $searchResults
        );
    }

    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @return ResponseModelInterface
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     */
    private function searchEbayAdvanced(SearchModelInterface $model): ResponseModelInterface
    {
        return $this->finder->findEbayProductsAdvanced($model);
    }
}