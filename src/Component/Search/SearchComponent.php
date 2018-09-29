<?php

namespace App\Component\Search;

use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;

class SearchComponent
{
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * SearchComponent constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     */
    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
    }

    public function searchEbay()
    {

    }

    public function searchEtsy()
    {

    }
}