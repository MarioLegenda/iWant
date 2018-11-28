<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Component\Search\Ebay\Business\ResultsFetcher\Filter\FilterApplierInterface;
use App\Component\Search\Ebay\Model\Request\SearchModel;

class FetcherFactory
{
    /**
     * @var SourceUnFilteredFetcher $sourceUnfilteredFetcher
     */
    private $sourceUnFilteredFetcher;
    /**
     * @var LowestPriceFetcher $lowestPrice
     */
    private $lowestPrice;
    /**
     * @var HighestPriceFetcher $highestPriceFetcher
     */
    private $highestPriceFetcher;
    /**
     * FetcherFactory constructor.
     * @param SourceUnFilteredFetcher $sourceUnFilteredFetcher
     * @param LowestPriceFetcher $lowestPrice
     * @param HighestPriceFetcher $highestPriceFetcher
     */
    public function __construct(
        SourceUnFilteredFetcher $sourceUnFilteredFetcher,
        LowestPriceFetcher $lowestPrice,
        HighestPriceFetcher $highestPriceFetcher
    ) {
        $this->sourceUnFilteredFetcher = $sourceUnFilteredFetcher;
        $this->lowestPrice = $lowestPrice;
        $this->highestPriceFetcher = $highestPriceFetcher;
    }

    public function decideFetcher(SearchModel $model): object
    {
        if ($model->isLowestPrice()) {
            return $this->lowestPrice;
        }

        if ($model->isHighestPrice()) {
            return $this->highestPriceFetcher;
        }

        return $this->sourceUnFilteredFetcher;
    }
}