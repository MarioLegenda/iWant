<?php

namespace App\Ebay\Library\ItemFilter;

use App\Ebay\Library\Dynamic\BaseDynamic;

class CategoryId extends BaseDynamic
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
                '%s can have only one value and it has to be a valid category id',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        $categories = $dynamicValue[0];

        foreach ($categories as $category) {
            if (!is_int($category)) {
                $message = sprintf(
                    '%s has to be an integer',
                    $category
                );

                throw new \RuntimeException($message);
            }
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

        $counter = 0;
        $final = '';
        foreach ($dynamicValue as $filter) {
            $final.='categoryId('.$counter.')='.$filter.'&';

            $counter++;
        }

        return $final;
    }
}