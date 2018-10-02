<?php

namespace App\Component\Search;

use App\Component\Search\Business\Finder;
use App\Component\Search\Model\Request\SearchModel;

class SearchComponent
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * SearchComponent constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     */
    public function searchEbay(SearchModel $model): iterable
    {
        return $this->finder->findEbayProducts($model);
    }

    public function searchEtsy(SearchModel $model): iterable
    {

    }
}