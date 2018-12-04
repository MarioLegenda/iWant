<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Component\Search\Ebay\Model\Request\SearchModel;

interface FetcherInterface
{
    /**
     * @param SearchModel $model
     * @param array $replacements
     * @return mixed
     */
    public function getResults(SearchModel $model, array $replacements = []);
}