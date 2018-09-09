<?php

namespace App\Etsy\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class Results implements
    ArrayNotationInterface,
    \Countable,
    \IteratorAggregate,
    \ArrayAccess
{
    /**
     * @var Result[] $results
     */
    private $results;
    /**
     * Results constructor.
     * @param array $results
     */
    public function __construct(array $results)
    {
        $this->results = $this->createResultObjects($results);
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        $results = [];

        $resultsGen = Util::createGenerator($this->results);
        foreach ($resultsGen as $item) {
            /** @var Result $entry */
            $entry = $item['item'];

            $results[] = $entry->toArray();
        }

        return $results;
    }
    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->results);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->results);
    }
    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->results);
    }
    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->results[$offset];
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
     * @param array $results
     * @return iterable
     */
    private function createResultObjects(array $results): iterable
    {
        $createdResults = [];
        $resultsGen = Util::createGenerator($results);
        foreach ($resultsGen as $item) {
            $entry = $item['item'];
            $createdResults[] = new Result($entry);
        }

        return $createdResults;
    }
}