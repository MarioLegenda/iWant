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
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $dynamicName = $this->getDynamicMetadata()->getName();

        $validValues = ['entriesPerPage', 'pageNumber'];

        if (!$this->genericValidation($dynamicValue, 1)) {
            $message = sprintf(
                '%s can have accept an array with one value; an array with values %s',
                PaginationInput::class,
                implode(', ', $validValues)
            );

            throw new \RuntimeException($message);
        }

        $filter = $dynamicValue[0];

        if (!is_array($filter)) {
            $message = sprintf(
                '%s can have accept an array with one value; an array with values %s',
                PaginationInput::class,
                implode(', ', $validValues)
            );

            throw new \RuntimeException($message);
        }

        $validDiff = array_diff(array_keys($filter), $validValues);

        if (!empty($validDiff)) {
            $message = sprintf(
                '%s can have accept an array with one value; an array with values %s. Invalid entries are: %s',
                PaginationInput::class,
                implode(', ', $validValues),
                implode(', ', $validDiff)
            );

            throw new \RuntimeException($message);
        }

        foreach ($filter as $key => $f) {
            if (in_array($key, $validValues) === false) {
                $message = sprintf(
                    'Invalid paginationInput entry \'%s\'. Valid entries are %s',
                    $key,
                    implode(', ', $validValues)
                );

                throw new \RuntimeException($message);
            }

            if (!is_int($f)) {
                $message = sprintf(
                    '%s can contain only %s and their arguments have to be integers. Value \'%s\' given for %s',
                    $dynamicName,
                    implode(', ', $validValues),
                    $f,
                    $key
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
    public function urlify(int $counter): string
    {
        $dynamicValue = $this->getDynamicMetadata()->getDynamicValue();
        $finalEntry = '';

        foreach ($dynamicValue[0] as $key => $f) {
            $finalEntry.='paginationInput.'.$key.'='.$f.'&';
        }

        return $finalEntry;
    }
}