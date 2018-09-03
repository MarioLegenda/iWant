<?php

namespace App\Library\Tools;

use App\Library\Util\TypedRecursion;

class LockedImmutableGenericHashSet extends LockedImmutableHashSet
{
    /**
     * @param array $data
     * @return LockedImmutableHashSet
     */
    public static function create(array $data): LockedImmutableHashSet
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

    }
    /**
     * @inheritdoc
     */
    public function toArray(): iterable
    {
        if (is_array($this->data) and empty($this->data)) {
            return [];
        }

        $typedRecursion = new TypedRecursion($this->data);

        return $typedRecursion->iterate();
    }
}