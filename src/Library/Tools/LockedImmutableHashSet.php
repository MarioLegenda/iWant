<?php

namespace App\Library\Tools;

use App\Library\Infrastructure\CollectionInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;

class LockedImmutableHashSet implements CollectionInterface
{
    /**
     * @var array $data
     */
    protected $data;
    /**
     * @param array $data
     * @return LockedImmutableHashSet
     */
    public static function create(array $data)
    {
        return new LockedImmutableHashSet($data);
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
                $lockedData[$key] = LockedImmutableHashSet::create($item);
            } else {
                $lockedData[$key] = $item;
            }
        }

        $this->data = $lockedData;
    }
    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return count($this->data);
    }
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }
    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }
    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }
    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->throwUsageException();
    }
    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->throwUsageException();
    }
    /**
     * @inheritdoc
     */
    public function toArray(): iterable
    {
        if (empty($this->data)) {
            return [];
        }

        $typedRecursion = new TypedRecursion($this->data);

        return $typedRecursion->iterate();
    }
    /**
     * @param \Closure $filter
     * @return mixed
     */
    public function filter(\Closure $filter)
    {
        return $filter->__invoke($this->data);
    }
    /**
     * @return \Generator
     */
    public function createGenerator(): \Generator
    {
        return Util::createGenerator($this->data);
    }
    /**
     * @throws \RuntimeException
     */
    private function throwUsageException()
    {
        $message = sprintf('Locked immutable hash set cannot unset values or keys');

        throw new \RuntimeException($message);
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

        $dataGen = Util::createGenerator($data);

        foreach ($dataGen as $entry) {
            $key = $entry['key'];

            if (!is_string($key)) {
                $message = sprintf(
                    'Locked immutable hashed set accepts only string array keys',
                    $key
                );

                throw new \RuntimeException($message);
            }
        }
    }
}