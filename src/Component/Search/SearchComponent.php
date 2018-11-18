<?php

namespace App\Component\Search;

use App\Component\Search\Ebay\Business\SearchAbstraction;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class SearchComponent
{
    /**
     * @var SearchAbstraction $searchAbstraction
     */
    private $searchAbstraction;
    /**
     * SearchComponent constructor.
     * @param SearchAbstraction $searchAbstraction
     */
    public function __construct(
        SearchAbstraction $searchAbstraction
    ) {
        $this->searchAbstraction = $searchAbstraction;
    }

    public function saveProducts(SearchModel $model): void
    {
        $this->searchAbstraction->getProducts($model);
    }

    public function getProductsPaginated(SearchModel $model)
    {
        $listing = $this->searchAbstraction->paginateListingAutomatic($model);

        return $this->searchAbstraction->translateListing($listing, $model);
    }
}