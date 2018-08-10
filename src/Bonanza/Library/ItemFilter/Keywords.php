<?php

namespace App\Bonanza\Library\ItemFilter;

use App\Bonanza\Library\Dynamic\BaseDynamic;

class Keywords extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s has to be an array with one value',
                SortOrder::class
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_array($filter)) {
            $message = sprintf(
                'Bonanza API %s has to be an array with \'keywords\' key and a string in that key',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        if (!array_key_exists('keywords', $filter)) {
            $message = sprintf(
                'Bonanza API %s has to be an array with \'keywords\' key and a string in that key',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        if (!is_string($filter['keywords'])) {
            $message = sprintf(
                'Bonanza API %s has to be an array with \'keywords\' key and a string in that key',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
    /**
     * @param int $counter
     * @return string|array
     */
    public function urlify(int $counter = null)
    {
        return $this->getDynamicMetadata()->getDynamicValue()[0];
    }
}