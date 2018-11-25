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
     * @var HighestPriceFetcher $highestPriceFetcher
     */
    private $highestPriceFetcher;
    /**
     * FetcherFactory constructor.
     * @param SourceUnFilteredFetcher $sourceUnFilteredFetcher
     * @param LowestPriceFetcher $lowestPriceFetcher
     * @param HighestPriceFetcher $highestPriceFetcher
     */
    public function __construct(
        SourceUnFilteredFetcher $sourceUnFilteredFetcher,
        LowestPriceFetcher $lowestPriceFetcher,
        HighestPriceFetcher $highestPriceFetcher
    ) {
        $this->sourceUnFilteredFetcher = $sourceUnFilteredFetcher;
        $this->lowestPriceFetcher = $lowestPriceFetcher;
        $this->highestPriceFetcher = $highestPriceFetcher;
    }

    public function decideFetcher(SearchModel $model): object
    {
        if ($model->isLowestPrice()) {
            return $this->lowestPriceFetcher;
        }

        if ($model->isHighestPrice()) {
            return $this->highestPriceFetcher;
        }

        return $this->sourceUnFilteredFetcher;
    }
}