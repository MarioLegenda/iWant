<?php

namespace App\Component\Search;

use App\Component\Search\Ebay\Business\SearchAbstraction;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Ebay\Library\Information\GlobalIdInformation;

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
        $products = $this->searchAbstraction->getEbayProductsByGlobalId($model);

        return [
            'globalIdInformation' => GlobalIdInformation::instance()->getTotalInformation($model->getGlobalId()),
            'items' => $products
        ];
    }
}