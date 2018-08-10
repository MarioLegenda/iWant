<?php

namespace App\Bonanza\Library\ItemFilter;

use App\Bonanza\Library\Dynamic\BaseDynamic;

class BuyerPostalCode extends BaseDynamic
{
    /**
     * @return bool
     */
    public function validateDynamic() : bool
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can have only one value, true or false',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        if (!is_string($dynamicValue[0])) {
            $message = sprintf(
                '%s has to be a string',
                $dynamicName
            );

            throw new \RuntimeException($message);
        }

        return true;
    }
    /**
     * @param int|null $counter
     * @return mixed
     */
    public function urlify(int $counter = null)
    {
        return $this->getDynamicMetadata()->getDynamicValue()[0];
    }
}