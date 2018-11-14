<?php

namespace App\Component\Search;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Business\Finder as EbayFinder;
use App\Component\Search\Ebay\Business\ModelPreparationFactory;
use App\Component\Search\Ebay\Business\PreparedEbayResponseAbstraction;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Component\Search\Etsy\Business\Finder as EtsyFinder;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;

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
        $searchResponseModels = $this->modelPreparationFactory->prepareSearchItems(
            $model
        );

        return $searchResponseModels;
    }

    public function getOptionsForSearchListing()
    {

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