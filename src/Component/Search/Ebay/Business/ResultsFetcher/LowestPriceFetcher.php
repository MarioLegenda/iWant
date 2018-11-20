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

class LowestPriceFetcher implements FetcherInterface
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
    /**
     * @param SearchModel $model
     * @param array $replacementData
     * @return TypedArray|mixed
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getResults(SearchModel $model, array $replacementData = [])
    {
        if (!empty($replacementData)) {
            $message = sprintf(
                'Invalid usage of lowest price filter. %s is a specific custom filter and cannot be controlled as he is its only source of truth',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        $lowestPriceIdentifier = $model->getUniqueName([
            'lowestPrice' => true,
        ]);

        if ($this->searchResponseCacheImplementation->isStored($lowestPriceIdentifier)) {
            /** @var SearchCache $presentationResults */
            $presentationResults = $this->searchResponseCacheImplementation->getStored($lowestPriceIdentifier);

            return json_decode($presentationResults->getProductsResponse(), true);
        }

        $sourceUnFilteredResults = $this->sourceUnFilteredFetcher->getFreshResults($model);

        /** @var TypedArray $lowestPriceGroupedResults */
        $lowestPriceGroupedResults = Grouping::inst()->groupByPriceLowest(
            $this->convertToPriceGrouping($sourceUnFilteredResults)
        );

        $this->searchResponseCacheImplementation->store(
            $model->getUniqueName(['lowestPrice' => true]),
            $model->getInternalPagination()->getPage(),
            jsonEncodeWithFix($lowestPriceGroupedResults->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
        );

        return $lowestPriceGroupedResults;
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