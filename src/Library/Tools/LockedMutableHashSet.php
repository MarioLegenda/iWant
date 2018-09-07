<?php

namespace App\Library\Tools;

use App\Library\Infrastructure\CollectionInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;

class LockedMutableHashSet implements CollectionInterface
{
    /**
     * @var array $keys
     */
    private $keys;
    /**
     * @var array $data
     */
    protected $data;
    /**
     * @param array $keys
     * @param array $data
     * @return LockedMutableHashSet
     */
    public static function create(array $keys, array $data = []): LockedMutableHashSet
    {
        return new LockedMutableHashSet($keys, $data);
    }
    /**
     * LockedImmutableHashSet constructor.
     * @param array $keys
     * @param array $data
     *
     * Accepts only array keys that are set later
     */
    private function __construct(array $keys, array $data = [])
    {
        $this->validate($keys, $data);

        $this->keys = $keys;

        $dataGen = Util::createGenerator($data);

        foreach ($dataGen as $entry) {
            $key = $entry['key'];
            $item = $entry['item'];

            if (!in_array($key, $this->keys)) {
                $message = sprintf(
                    'Invalid offset %s. Valid offsets are %s',
                    $key,
                    implode(', ', $this->keys)
                );

                throw new \RuntimeException($message);
            }

            $this[$key] = $item;
        }
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
        if (!in_array($offset, $this->keys)) {
            $message = sprintf(
                'Invalid offset %s. Valid offsets are %s',
                $offset,
                implode(', ', $this->keys)
            );

            throw new \RuntimeException($message);
        }

        $this->data[$offset] = $value;
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
        $message = sprintf(
            '%s set cannot unset values or keys',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param array $keys
     * @param array $data
     * @throws \RuntimeException
     */
    protected function validate(array $keys, array $data)
    {
        if (empty($keys)) {
            $message = sprintf('Locked immutable hash set does not accept empty values');

            throw new \RuntimeException($message);
        }

        $dataGen = Util::createGenerator($keys);

        foreach ($dataGen as $entry) {
            $key = $entry['item'];

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