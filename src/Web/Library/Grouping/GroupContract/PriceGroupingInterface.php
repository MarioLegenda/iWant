<?php

namespace App\Web\Library\Grouping\GroupContract;

interface PriceGroupingInterface
{
    /**
     * @return float
     */
    public function getPriceForGrouping(): float;
}