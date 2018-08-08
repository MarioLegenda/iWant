<?php

namespace App\Library\Tools;

class LockedImmutableGenericHashSet extends LockedImmutableHashSet
{
    /**
     * @param array $data
     * @return LockedImmutableHashSet
     */
    public static function create(array $data)
    {
        return new LockedImmutableGenericHashSet($data);
    }
    /**
     * LockedImmutableHashSet constructor.
     * @param array $data
     */
    private function __construct(array $data)
    {
        $this->validate($data);

        $lockedData = [];

        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $lockedData[$key] = LockedImmutableGenericHashSet::create($item);
            } else {
                $lockedData[$key] = $item;
            }
        }

        $this->data = $lockedData;
    }

    /**
     * @param array $data
     * @throws \RuntimeException
     */
    protected function validate(array $data)
    {
        if (empty($data)) {
            $message = sprintf('Locked immutable hash set does not accept empty values');

            throw new \RuntimeException($message);
        }
    }
}