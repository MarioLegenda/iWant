<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class Condition extends BaseDynamic implements ItemFilterInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        $allowedValues = [
            'text-values' => ['New', 'Used', 'Unspecified'],
            'id-values' => [1000, 1500, 1750, 2000, 2500, 3000, 4000, 5000, 6000, 7000],
        ];

        if (!$this->genericValidation($dynamicValue)) {
            $message = sprintf(
                'Invalid argument for item filter \'%s\'. Please, refer to http://developer.ebay.com/devzone/finding/callref/types/ItemFilterType.html',
                $this->getDynamicMetadata()->getName()
            );

            throw new \RuntimeException($message);
        }

        $uniques = array_unique($dynamicValue);

        foreach ($uniques as $val) {
            if (!in_array($val, $allowedValues['text-values']) and !in_array($val, $allowedValues['id-values'])) {
                $message = sprintf(
                    'Invalid argument for item filter \'%s\'. Please, refer to http://developer.ebay.com/devzone/finding/callref/types/ItemFilterType.html',
                    $this->getDynamicMetadata()->getName()
                );

                throw new \RuntimeException($message);
            }
        }

        return true;
    }
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter = null) : string
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $finalEntry = sprintf('itemFilter(%d).name=Condition&', $counter);

        $valueCounter = 0;
        foreach ($dynamicValue as $key => $f) {
            $finalEntry.=sprintf('itemFilter(%d).value(%d)=%s&', $counter, $valueCounter, $f);

            $valueCounter++;
        }

        return $finalEntry;
    }
}