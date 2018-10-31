<?php

namespace App\Component\Search;

use App\Cache\Implementation\PreparedResponseCacheImplementation;
use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Factory\SearchResponseModelFactory;
use App\Component\Search\Ebay\Business\Factory\SpreadFactory\SpreadFactory;
use App\Component\Search\Ebay\Business\Finder as EbayFinder;
use App\Component\Search\Ebay\Business\ModelPreparationFactory;
use App\Component\Search\Ebay\Business\PreparedEbayResponseAbstraction;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Component\Search\Etsy\Business\Finder as EtsyFinder;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Library\Util\TypedRecursion;
use App\Web\Library\Grouping\Grouping;

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
     * @var ModelPreparationFactory $modelPreparationFactory
     */
    private $modelPreparationFactory;
    /**
     * @var PreparedEbayResponseAbstraction $preparedEbayResponseAbstraction
     */
    private $preparedEbayResponseAbstraction;
    /**
     * SearchComponent constructor.
     * @param EbayFinder $ebayFinder
     * @param EtsyFinder $etsyFinder
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param ModelPreparationFactory $modelPreparationFactory
     * @param PreparedEbayResponseAbstraction $preparedEbayResponseAbstraction
     */
    public function __construct(
        EbayFinder $ebayFinder,
        EtsyFinder $etsyFinder,
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        ModelPreparationFactory $modelPreparationFactory,
        PreparedEbayResponseAbstraction $preparedEbayResponseAbstraction
    ) {
        $this->ebayFinder = $ebayFinder;
        $this->etsyFinder = $etsyFinder;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
        $this->modelPreparationFactory = $modelPreparationFactory;
        $this->preparedEbayResponseAbstraction = $preparedEbayResponseAbstraction;
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

        $searchResponseModels = $this->modelPreparationFactory->prepareSearchItems(
            $model,
            $storedResponse
        );

        if ($model->isLowestPrice()) {
            return Grouping::inst()->groupByPriceLowest($searchResponseModels);
        }

        return $searchResponseModels;
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
        return $this->preparedEbayResponseAbstraction->getPreparedResponse($model);
    }
}