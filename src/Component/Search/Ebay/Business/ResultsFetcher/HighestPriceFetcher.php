<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Doctrine\Entity\SearchCache;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Web\Library\Grouping\GroupContract\PriceGroupingInterface;
use App\Web\Library\Grouping\Grouping;

class HighestPriceFetcher
{
    /**
     * @var SourceUnFilteredFetcher $sourceUnFilteredFetcher
     */
    private $sourceUnFilteredFetcher;
    /**
     * @var SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    private $searchResponseCacheImplementation;
    /**
     * LowestPriceFetcher constructor.
     * @param SourceUnFilteredFetcher $sourceUnFilteredFetcher
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     */
    public function __construct(
        SourceUnFilteredFetcher $sourceUnFilteredFetcher,
        SearchResponseCacheImplementation $searchResponseCacheImplementation
    ) {
        $this->sourceUnFilteredFetcher = $sourceUnFilteredFetcher;
        $this->searchResponseCacheImplementation = $searchResponseCacheImplementation;
    }

    public function getResults(SearchModel $model, array $replacementData = [])
    {
        $identifier = $model->getUniqueName();

        if ($this->searchResponseCacheImplementation->isStored($identifier)) {
            /** @var SearchCache $presentationResults */
            $presentationResults = $this->searchResponseCacheImplementation->getStored($identifier);

            return json_decode($presentationResults->getProductsResponse(), true);
        }

        $model->setHighestPrice(false);

        $results = $this->sourceUnFilteredFetcher->getResults($model);

        /** @var TypedArray $lowestPriceGroupedResults */
        $lowestPriceGroupedResults = Grouping::inst()->groupByPriceHighest(
            $this->convertToPriceGrouping($results)
        );

        $model->setHighestPrice(true);

        $this->searchResponseCacheImplementation->store(
            $model->getUniqueName(),
            $model->getPagination()->getPage(),
            jsonEncodeWithFix($lowestPriceGroupedResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
        );

        return $lowestPriceGroupedResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);
    }
    /**
     * @param iterable $iterable
     * @return iterable
     */
    private function convertToPriceGrouping(iterable $iterable): iterable
    {
        $iterableGen = Util::createGenerator($iterable);

        $converted = TypedArray::create('integer', PriceGroupingInterface::class);

        foreach ($iterableGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];

            $converted[$key] = new class($item) implements PriceGroupingInterface, ArrayNotationInterface {
                /**
                 * @var array $entry
                 */
                private $entry;
                /**
                 *  constructor.
                 * @param array $entry
                 */
                public function __construct(array $entry)
                {
                    $this->entry = $entry;
                }
                /**
                 * @return float
                 */
                public function getPriceForGrouping(): float
                {
                    return $this->entry['price']['price'];
                }

                public function toArray(): iterable
                {
                    return $this->entry;
                }
            };
        }

        return $converted;
    }
}