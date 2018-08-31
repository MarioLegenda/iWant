<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

interface FindingApiRootItemInterface
{
    public function getSearchResultsCount(): int;
}