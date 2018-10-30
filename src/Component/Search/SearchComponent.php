<?php

namespace App\Component\Search;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Factory\SearchResponseModelFactory;
use App\Component\Search\Ebay\Business\Finder as EbayFinder;
use App\Component\Search\Ebay\Business\ModelPreparationFactory;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Component\Search\Etsy\Business\Finder as EtsyFinder;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Library\Util\TypedRecursion;

class SearchComponent
{
    /**
     * @var EbayFinder $ebayFinder
     */
    private $ebayFinder;
    /**
     * @var EtsyFinder $etsyFinder
     */
    private $etsyFinder;
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
     * @param EbayFinder $ebayFinder
     * @param EtsyFinder $etsyFinder
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param PreparedResponseCacheImplementation $preparedResponseCacheImplementation
     * @param ModelPreparationFactory $modelPreparationFactory
     * @param SearchResponseModelFactory $searchResponseModelFactory
     */
    public function __construct(
        EbayFinder $ebayFinder,
        EtsyFinder $etsyFinder,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        PreparedResponseCacheImplementation $preparedResponseCacheImplementation,
        ModelPreparationFactory $modelPreparationFactory,
        SearchResponseModelFactory $searchResponseModelFactory
    ) {
        $this->searchResponseModelFactory = $searchResponseModelFactory;
        $this->ebayFinder = $ebayFinder;
        $this->etsyFinder = $etsyFinder;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->preparedResponseCacheImplementation = $preparedResponseCacheImplementation;
        $this->modelPreparationFactory = $modelPreparationFactory;
    }
    /**
     * @param PreparedItemsSearchModel $model
     * @return iterable|null
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function findEbaySearchByUniqueName(PreparedItemsSearchModel $model): ?iterable
    {
        if (!$this->searchResponseCacheImplementation->isStored($model->getUniqueName())) {
            return null;
        }

        $storedResponse = json_decode($this->searchResponseCacheImplementation->getStored($model->getUniqueName()), true);

        return $this->modelPreparationFactory->prepareSearchItems(
            $model,
            $storedResponse
        );
    }
    /**
     * @param EbaySearchModel $model
     * @return PreparedEbayResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Throwable
     */
    public function prepareEbayProductsAdvanced(EbaySearchModel $model): PreparedEbayResponse
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

        if ($searchResults->isEmpty()) {
            return $preparedEbayResponse;
        }

        $searchResponseModels = $this->searchResponseModelFactory->fromIterable(
            $uniqueName,
            $globalId,
            $searchResults
        );

        $this->preparedResponseCacheImplementation->store(
            $uniqueName,
            json_encode($preparedEbayResponse->toArray())
        );

        $this->searchResponseCacheImplementation->store(
            $uniqueName,
            $model->getPagination()->getPage(),
            json_encode($searchResponseModels->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
        );
        
        if ($exceptionThrown instanceof \Exception) {
            throw $exceptionThrown;
        }

        return $preparedEbayResponse;
    }
    /**
     * @param EbaySearchModel $model
     * @return FindingApiResponseModelInterface
     */
    private function searchEbayAdvanced(EbaySearchModel $model): ResponseModelInterface
    {
        return $this->ebayFinder->findEbayProductsAdvanced($model);
    }
}