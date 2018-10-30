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
use App\Library\Util\TypedRecursion;

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

        $exceptionThrown = null;

        try {
            $response = $this->searchEbayAdvanced($model);
        } catch (\Throwable $e) {
            $exceptionThrown = $e;
        }

        $globalId = $model->getGlobalId();
        $totalEntries = $response->getPaginationOutput()->getTotalEntries();
        $entriesPerPage = $response->getPaginationOutput()->getEntriesPerPage();

        $preparedEbayResponse = new PreparedEbayResponse(
            $uniqueName,
            GlobalIdInformation::instance()->getTotalInformation($globalId),
            $globalId,
            $totalEntries,
            $entriesPerPage,
            $exceptionThrown instanceof \Exception
        );

        /** @var SearchResultsContainer $searchResults */
        $searchResults = $response->getSearchResults();

        $searchResponseModels = $this->searchResponseModelFactory->fromIterable(
            $uniqueName,
            $globalId,
            $searchResults
        );

        $this->preparedResponseCacheImplementation->store(
            $uniqueName,
            json_encode($preparedEbayResponse->toArray())
        );

        $toEncode = $searchResponseModels->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);
        $encoded = json_encode($toEncode);

        if ($encoded === false) {
            $toEncode = utf8ize($toEncode);
        }

        $this->searchResponseCacheImplementation->store(
            $uniqueName,
            $model->getPagination()->getPage(),
            json_encode($toEncode)
        );

        if ($exceptionThrown instanceof \Exception) {
            throw $exceptionThrown;
        }

        return $preparedEbayResponse;
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