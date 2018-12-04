<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Fetcher;

use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;

interface FetcherInterface
{
    /**
     * @param SearchModel|SearchModelInterface $model
     * @param array $replacements
     * @return mixed
     */
    public function getResults(SearchModelInterface $model, array $replacements = []);
}