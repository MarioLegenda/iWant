<?php

namespace App\Component\Search;

use App\Component\Ebay\Search\Request\Model\SearchRequestModel as EbaySearchRequestModel;
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

    public function searchEbay(EbaySearchRequestModel $searchRequestModel)
    {

    }

    public function searchEtsy()
    {

    }
}