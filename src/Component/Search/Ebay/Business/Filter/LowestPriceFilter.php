<?php

namespace App\Component\Search\Ebay\Business\Filter;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Web\Library\Grouping\GroupContract\PriceGroupingInterface;
use App\Web\Library\Grouping\Grouping;

class LowestPriceFilter implements FilterInterface
{
    /**
     * @param array $entries
     * @return array
     */
    public function filter(array $entries): array
    {
        return Grouping::inst()->groupByPriceLowest($this->createGroupingCompatibleObjects($entries))->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION);
    }

    private function createGroupingCompatibleObjects(array $entries): iterable
    {
        $entriesGen = Util::createGenerator($entries);
        $groupingObject = TypedArray::create('integer', PriceGroupingInterface::class);

        foreach ($entriesGen as $entry) {
            $item = $entry['item'];

            $groupingObject[] = new class($item) implements PriceGroupingInterface, ArrayNotationInterface {
                /**
                 * @var array $item
                 */
                private $item;
                /**
                 *  constructor.
                 * @param array $item
                 */
                public function __construct(array $item)
                {
                    $this->item = $item;
                }
                /**
                 * @return float
                 */
                public function getPriceForGrouping(): float
                {
                    return $this->item['price']['price'];
                }
                /**
                 * @return iterable
                 */
                public function toArray(): iterable
                {
                    return $this->item;
                }
            };
        }

        return $groupingObject;
    }
}