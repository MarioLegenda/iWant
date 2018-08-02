<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\DynamicMetadata;

interface ItemFilterInterface
{
    /**
     * @return DynamicMetadata
     */
    public function getDynamicMetadata(): DynamicMetadata;
}