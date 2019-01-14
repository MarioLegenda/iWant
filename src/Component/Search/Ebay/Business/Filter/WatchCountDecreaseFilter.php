<?php

namespace App\Component\Search\Ebay\Business\Filter;

class WatchCountDecreaseFilter implements FilterInterface
{
    /**
     * @param array $entries
     * @return array
     */
    public function filter(array $entries): array
    {
        usort($entries, function(array $a, array $b) {
            $watchCountA = $a['listingInfo']['watchCount'];
            $watchCountB = $b['listingInfo']['watchCount'];

            return $watchCountA >= $watchCountB;
        });

        return $entries;
    }
}