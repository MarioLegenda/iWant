<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class Seller extends BaseDynamic implements ItemFilterInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue)) {
            $message = sprintf(
                '%s item filter is either empty or not a valid data type. It has to be an array data type',
                Seller::class
            );

            throw new \RuntimeException($message);
        }

        if (count($dynamicValue) > 100) {
            $message = sprintf(
                '%s has to be a valid seller name. Up to a 100 sellers can be specified',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        $filter = array_unique($dynamicValue[0]);

        $invalidItems = [];
        foreach ($filter as $item) {
            if (!is_string($item)) {
                $invalidItems[] = $item;
            }
        }

        if (!empty($invalidItems)) {
            $message = sprintf(
                '%s accepts an array of valid seller names. A seller name has to be a string. Invalid values are %s',
                $dynamicName,
                implode(', ', $invalidItems)
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
    /**
     * @param int|null $counter
     * @return string
     */
    public function urlify(int $counter = null): string
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $finalEntry = sprintf('itemFilter(%d)=Seller&', 0);

        $valueCounter = 0;
        foreach ($dynamicValue[0] as $key => $f) {
            $finalEntry.=sprintf('itemFilter(%d).value(%d)=%s&', $counter, $valueCounter, $f);
        }

        return $finalEntry;
    }
}