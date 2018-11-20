<?php

namespace App\Component\Search;

use App\Component\Search\Ebay\Business\SearchAbstraction;
use App\Component\Search\Ebay\Business\SearchModelValidator;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class SearchComponent
{
    /**
     * @var SearchAbstraction $searchAbstraction
     */
    private $searchAbstraction;
    /**
     * @var SearchModelValidator $searchModelValidator
     */
    private $searchModelValidator;
    /**
     * SearchComponent constructor.
     * @param SearchAbstraction $searchAbstraction
     * @param SearchModelValidator $searchModelValidator
     */
    public function __construct(
        SearchAbstraction $searchAbstraction,
        SearchModelValidator $searchModelValidator
    ) {
        $this->searchAbstraction = $searchAbstraction;
        $this->searchModelValidator = $searchModelValidator;
    }
    /**
     * @param SearchModel $model
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveProducts(SearchModel $model): void
    {
        $this->searchModelValidator->validate($model);

        $this->searchAbstraction->getProducts($model);
    }
    /**
     * @param SearchModel $model
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getProductsPaginated(SearchModel $model)
    {
        $this->searchModelValidator->validate($model);

        $listing = $this->searchAbstraction->paginateListingAutomatic($model);

        return $this->searchAbstraction->translateListing($listing, $model);
    }

    public function getProductsRange(SearchModel $model): array
    {
        $this->searchModelValidator->validate($model);

        return $this->searchAbstraction->getListingRange($model);
    }
}