<?php

namespace App\Component\Search;

use App\Component\Search\Ebay\Business\SearchAbstraction;
use App\Component\Search\Ebay\Business\SearchModelValidator;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;

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
     * @param SearchModel|SearchModelInterface $model
     */
    public function saveProducts(SearchModelInterface $model): void
    {
        $this->searchModelValidator->validate($model);

        $this->searchAbstraction->getProducts($model);
    }
    /**
     * @param SearchModelInterface|SearchModel $model
     * @return iterable
     */
    public function getProductsGrouped(SearchModelInterface $model): iterable
    {
        $products = $this->searchAbstraction->getProducts($model);

        return $products;
    }
    /**
     * @param SearchModel|SearchModelInterface $model
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getProductsPaginated(SearchModelInterface $model): iterable
    {
        $this->searchModelValidator->validate($model);

        $listing = $this->searchAbstraction->paginateListingAutomatic($model);

        return $this->searchAbstraction->translateListing($listing, $model);
    }
    /**
     * @param SearchModel $model
     * @return array
     *
     * @deprecated Will be implemented later when ? users ? come
     */
    public function getProductsRange(SearchModel $model): array
    {
        $this->searchModelValidator->validate($model);

        return $this->searchAbstraction->getListingRange($model);
    }
}