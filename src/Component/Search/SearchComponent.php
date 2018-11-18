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
    /**
     * @param SearchModel $model
     * @return array
     */
    public function getEbayProductsByGlobalId(SearchModel $model): array
    {
        return $this->searchAbstraction->getEbayProductsByGlobalId($model);
    }
}