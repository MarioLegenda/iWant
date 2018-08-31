<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Information\OutputSelectorInformation;

class OutputSelector extends BaseDynamic implements ItemFilterInterface
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        $validSelectors = OutputSelectorInformation::instance()->getAll();

        foreach ($dynamicValue as $filter) {
            if (in_array($filter, $validSelectors) === false) {
                $message = sprintf(
                    'Invalid output selector \'%s\'. Valid outputSelector types are %s',
                    $filter,
                    implode(', ', $validSelectors)
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

        $counter = 0;
        $final = '';
        foreach ($dynamicValue as $filter) {
            $final.='outputSelector('.$counter.')='.$filter.'&';

            $counter++;
        }

        return $final;
    }
}