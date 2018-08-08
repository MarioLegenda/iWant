<?php

namespace App\Amazon\Library\ItemFilter;

use App\Amazon\Library\Dynamic\BaseDynamic;

class SearchIndex extends BaseDynamic implements ItemFilterInterface
{
    public function validateDynamic(): bool
    {
        $dynamicValue = $this->dynamicMetadata->getDynamicValue();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can have only one value, true or false',
                Keywords::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_string($filter)) {
            $message = sprintf(
                '%s has to be a string',
                Keywords::class
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
}