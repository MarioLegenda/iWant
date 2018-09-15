<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class IncludeSelector extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        return true;
    }

    public function urlify(int $counter = null): string
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        return sprintf('includeSelector=%s', implode(',', $dynamicValue));
    }
}