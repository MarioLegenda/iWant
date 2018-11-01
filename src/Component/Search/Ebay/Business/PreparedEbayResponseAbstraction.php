<?php

namespace App\Component\Search\Ebay\Business;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Factory\SearchResponseModelFactory;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Grouping\Grouping;

/**
 * Class PreparedEbayResponseAbstraction
 * @package App\Component\Search\Ebay\Business
 *
 * ********************
 *
 * THIS IS JUST A FUCKING ABSTRACTION OVER THE SEARCH COMPONENT
 *
 * **********************
 */
class PreparedEbayResponseAbstraction
{
    /**
     * @var Finder $ebayFinder
     */
    private $ebayFinder;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * @var PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     */
    private $preparedResponseCacheImplementation;
    /**
     * @var ModelPreparationFactory $modelPreparationFactory
     */
    private $modelPreparationFactory;
    /**
     * @var SearchResponseModelFactory $searchResponseModelFactory
     */
    private $searchResponseModelFactory;
    /**
     * SearchComponent constructor.
     * @param Finder $ebayFinder
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     * @param ModelPreparationFactory $modelPreparationFactory
     * @param SearchResponseModelFactory $searchResponseModelFactory
     */
    public function __construct(
        Finder $ebayFinder,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        PreparedResponseCacheImplementation $preparedResponseCacheImplementation,
        ModelPreparationFactory $modelPreparationFactory,
        SearchResponseModelFactory $searchResponseModelFactory
    ) {
        $this->searchResponseModelFactory = $searchResponseModelFactory;
        $this->ebayFinder = $ebayFinder;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->preparedResponseCacheImplementation = $preparedResponseCacheImplementation;
        $this->modelPreparationFactory = $modelPreparationFactory;
    }
    /**
     * @param SearchModel $model
     * @return PreparedEbayResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Throwable
     */
    public function getPreparedResponse(SearchModel $model)
    {
        if ($model->isLowestPrice()) {
            $lowestPriceUniqueName = md5(serialize($model));

            if ($this->preparedResponseCacheImplementation->isStored($lowestPriceUniqueName)) {
                $response = json_decode($this->preparedResponseCacheImplementation->getStored($lowestPriceUniqueName), true);

                return new PreparedEbayResponse(
                    $response['uniqueName'],
                    $response['globalIdInformation'],
                    $response['globalId'],
                    $response['totalEntries'],
                    $response['entriesPerPage'],
                    $response['isError']
                );
            }

            $preparedEbayResponse = $this->tryFindWithoutLowestPrice($model);

            if ($preparedEbayResponse instanceof PreparedEbayResponse) {
                return $preparedEbayResponse;
            }
        }

        $uniqueName = md5(serialize($model));

        if ($this->preparedResponseCacheImplementation->isStored($uniqueName)) {
            $response = json_decode($this->preparedResponseCacheImplementation->getStored($uniqueName), true);

            return new PreparedEbayResponse(
                $response['uniqueName'],
                $response['globalIdInformation'],
                $response['globalId'],
                $response['totalEntries'],
                $response['entriesPerPage'],
                $response['isError']
            );
        }

        $response = $this->searchEbayAdvanced($model);

        if ($response->isErrorResponse()) {
            return new PreparedEbayResponse(
                $uniqueName,
                GlobalIdInformation::instance()->getTotalInformation($globalId),
                $globalId,
                0,
                0,
                true
            );
        }

        $globalId = $model->getGlobalId();
        $totalEntries = $response->getPaginationOutput()->getTotalEntries();
        $entriesPerPage = $response->getPaginationOutput()->getEntriesPerPage();

        /** @var SearchResultsContainer $searchResults */
        $searchResults = $response->getSearchResults();

        try {
            $searchResponseModels = $this->searchResponseModelFactory->fromSearchResults(
                $uniqueName,
                $globalId,
                $searchResults
            );

            if ($model->isLowestPrice()) {
                $searchResponseModels = Grouping::inst()->groupByPriceLowest($searchResponseModels);
            }

            $searchResponseArray = $searchResponseModels->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

            $encodedSearchResponse = json_encode($searchResponseArray);

            if ($encodedSearchResponse === false) {
                $fixed = utf8ize($searchResponseArray);
                $encodedSearchResponse = json_encode($fixed);
            }
        } catch (\Throwable $e) {
            throw $e;
        }

        $preparedEbayResponse = new PreparedEbayResponse(
            $uniqueName,
            GlobalIdInformation::instance()->getTotalInformation($globalId),
            $globalId,
            $totalEntries,
            $entriesPerPage,
            false
        );

        $preparedResponseArray = $preparedEbayResponse->toArray();
        $encodedPreparedEbayResponse = json_encode($preparedResponseArray);

        $this->preparedResponseCacheImplementation->store(
            $uniqueName,
            $encodedPreparedEbayResponse
        );

        $this->searchResponseCacheImplementation->store(
            $uniqueName,
            $model->getPagination()->getPage(),
            $encodedSearchResponse
        );

        return $preparedEbayResponse;
    }
    /**
     * @param SearchModel $model
     * @return PreparedEbayResponse|null
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function tryFindWithoutLowestPrice(SearchModel $model): ?PreparedEbayResponse
    {
        $model->setLowestPrice(false);

        $uniqueNameWithoutLowestPrice = md5(serialize($model));

        if ($this->searchResponseCacheImplementation->isStored($uniqueNameWithoutLowestPrice)) {
            $storedResponse = $this->searchResponseCacheImplementation->getStored($uniqueNameWithoutLowestPrice);
            $storedDecodedResponse = json_decode($storedResponse, true);

            $searchResponseModels = $this->searchResponseModelFactory->fromArray(
                $uniqueNameWithoutLowestPrice,
                $model->getGlobalId(),
                $storedDecodedResponse
            );

            $searchResponseModels = Grouping::inst()->groupByPriceLowest($searchResponseModels);

            $searchResponseArray = $searchResponseModels->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);

            $encodedSearchResponse = json_encode($searchResponseArray);

            $model->setLowestPrice(true);

            $uniqueNameWithLowestPrice = md5(serialize($model));

            $this->searchResponseCacheImplementation->store(
                $uniqueNameWithLowestPrice,
                $model->getPagination()->getPage(),
                $encodedSearchResponse
            );

            $response = json_decode($this->preparedResponseCacheImplementation->getStored($uniqueNameWithoutLowestPrice), true);

            $preparedResponse = new PreparedEbayResponse(
                $uniqueNameWithLowestPrice,
                $response['globalIdInformation'],
                $response['globalId'],
                $response['totalEntries'],
                $response['entriesPerPage'],
                $response['isError']
            );

            $this->preparedResponseCacheImplementation->store(
                $uniqueNameWithLowestPrice,
                json_encode($preparedResponse->toArray())
            );
        }

        $model->setLowestPrice(true);

        return null;
    }
    /**
     * @param SearchModel $model
     * @return FindingApiResponseModelInterface
     */
    private function searchEbayAdvanced(SearchModel $model): ResponseModelInterface
    {
        return $this->ebayFinder->findEbayProductsAdvanced($model);
    }
}