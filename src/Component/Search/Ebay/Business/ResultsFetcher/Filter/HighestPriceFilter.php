<?php

namespace App\Component\Search\Ebay\Business\ResultsFetcher\Filter;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Web\Library\Grouping\GroupContract\PriceGroupingInterface;
use App\Web\Library\Grouping\Grouping;

class HighestPriceFilter implements FilterInterface
{
    public function filter(array $results)
    {
        /** @var TypedArray $highestPriceGroupedResult */
        $highestPriceGroupedResult = Grouping::inst()->groupByPriceHighest(
            $this->convertToPriceGrouping($results)
        );

        return $highestPriceGroupedResult->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);
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