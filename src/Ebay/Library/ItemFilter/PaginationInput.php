<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class PaginationInput extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        if (!$this->genericValidation($this->dynamicValue, 1)) {
            return false;
        }

        $validValues = array('entriesPerPage', 'pageNumber');

        $filter = $this->dynamicValue[0];
        foreach ($filter as $key => $f) {
            if (in_array($key, $validValues) === false) {
                $this->exceptionMessages[] = 'Invalid paginationInput entry \''.$key.'\'. Valid entries are '.implode(', ', $validValues);

                return false;
            }
        }

        return true;
    }
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter): string
    {
        $finalEntry = '';

        foreach ($this->dynamicValue[0] as $key => $f) {
            $finalEntry.='paginationInput.'.$key.'='.$f.'&';
        }

        return $finalEntry;
    }
}