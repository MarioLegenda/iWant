<?php

namespace App\Component\Search\Business;

use App\Component\Search\Model\Request\SearchModel;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;

class Finder
{
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * @var EtsyApiEntryPoint $etsyApiEntryPoint
     */
    private $etsyApiEntryPoint;

    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint,
        EtsyApiEntryPoint $etsyApiEntryPoint
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
    }
    /**
     * @param SearchModel $model
     * @return iterable
     */
    public function findEbayProducts(SearchModel $model): iterable
    {

    }
}