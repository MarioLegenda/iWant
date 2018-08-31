<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class EntriesPerPage extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {

        if (!$this->genericValidation($this->getDynamicMetadata()->getDynamicValue(), 1)) {
            $message = sprintf(
                '%s can have only one value and it has to be an integer',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue()[0];

        if (!is_int($dynamicValue)) {
            $message = sprintf(
                '%s can have only one value and it has to be an integer',
                get_class($this)
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
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue()[0];

        return 'paginationInput.entriesPerPage='.$dynamicValue.'&paginationInput.pageNumber=1';
    }
}