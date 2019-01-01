<?php

namespace App\Component\Search\Ebay\Business\Filter;

use App\Library\Util\Util;

class FixedPriceFilter implements FilterInterface
{
    /**
     * @param array $entries
     * @return array
     */
    public function filter(array $entries): array
    {
        $fixedPriceItems = [];

        $entriesGen = Util::createGenerator($entries);

        foreach ($entriesGen as $entry) {
            /** @var array $item */
            $item = $entry['item'];

            if ($item['isFixedPrice'] === true) {
                $fixedPriceItems[] = $item;
            }
        }

        return $fixedPriceItems;
    }
}