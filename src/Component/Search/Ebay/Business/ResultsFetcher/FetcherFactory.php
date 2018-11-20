<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Model\Request\SearchModel;

class FetcherFactory
{
    /**
     * @var SourceUnFilteredFetcher $sourceUnfilteredFetcher
     */
    private $sourceUnFilteredFetcher;
    /**
     * @var LowestPriceFetcher $lowestPriceFetcher
     */
    private $lowestPriceFetcher;
    /**
     * FetcherFactory constructor.
     * @param SourceUnFilteredFetcher $sourceUnFilteredFetcher
     * @param LowestPriceFetcher $lowestPriceFetcher
     */
    public function __construct(
        SourceUnFilteredFetcher $sourceUnFilteredFetcher,
        LowestPriceFetcher $lowestPriceFetcher
    ) {
        $this->sourceUnFilteredFetcher = $sourceUnFilteredFetcher;
        $this->lowestPriceFetcher = $lowestPriceFetcher;
    }

    public function decideFetcher(SearchModel $model): object
    {
        if ($model->isLowestPrice()) {
            return $this->lowestPriceFetcher;
        }

        if ($model->isHighestPrice()) {

        }

        return $this->sourceUnFilteredFetcher;
    }
}