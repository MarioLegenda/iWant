<?php

namespace App\Library\Tools;

use App\Library\Infrastructure\CollectionInterface;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;

class UnlockedImmutableHashSet implements CollectionInterface
{
    /**
     * @var array $validKeys
     */
    private $validKeys;
    /**
     * @var array $data
     */
    private $data;
    /**
     * @param array $validKeys
     * @return UnlockedImmutableHashSet
     */
    public static function create(array $validKeys)
    {
        return new UnlockedImmutableHashSet($validKeys);
    }
    /**
     * UnlockedImmutableHashSet constructor.
     * @param array $validKeys
     */
    private function __construct(array $validKeys)
    {
        $this->validate($validKeys);

        $this->validKeys = $validKeys;
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
        if (is_null($this->data) or empty($this->data)) {
            return false;
        }

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
        if (is_array($value)) {
            $this->data[$offset] = UnlockedImmutableHashSet::create(array_keys($value));
        }

        if (isset($this->data[$offset])) {
            $message = sprintf(
                '%s values can only be set once and cannot be changed later',
                get_class($this)
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
     * @return \Generator
     */
    public function createGenerator(): \Generator
    {
        return Util::createGenerator($this->data);
    }
    /**
     * @return iterable
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
     * @param array $validKeys
     */
    protected function validate(array $validKeys)
    {
        if (empty($validKeys)) {
            $message = sprintf(
                '%s does not permit empty valid keys',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }
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
}