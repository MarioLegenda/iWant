<?php

namespace App\Web\EntryPoint;

use App\Component\Search\SearchComponent;

class SearchEntryPoint
{
    /**
     * @var SearchComponent $searchComponent
     */
    private $searchComponent;
    /**
     * SearchEntryPoint constructor.
     * @param SearchComponent $searchComponent
     */
    public function __construct(
        SearchComponent $searchComponent
    ) {
        $this->searchComponent = $searchComponent;
    }
}