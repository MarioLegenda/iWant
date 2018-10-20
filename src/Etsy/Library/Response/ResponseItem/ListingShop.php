<?php

namespace App\Etsy\Library\Response\ResponseItem;

use App\Library\Util\Util;

class ListingShop implements ResultsInterface
{
    /**
     * @var array $iterableResults
     */
    private $iterableResults = [];
    /**
     * @var array $ratingResults
     */
    private $ratingResults = [];
    /**
     * ListingShop constructor.
     * @param array $results
     */
    public function __construct(
        array $results
    ) {
        $this->createResults($results);
    }
    /**
     * @return array
     */
    public function getIterableResults(): array
    {
        return $this->iterableResults;
    }
    /**
     * @return array
     */
    public function getRatingResults(): array
    {
        return $this->ratingResults;
    }
    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->iterableResults) + count($this->ratingResults);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator(array_merge($this->getIterableResults(), $this->getRatingResults()));
    }
    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        $this->throwUsageException();
    }
    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        $this->throwUsageException();
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
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'iterableResults' => $this->getIterableResults(),
            'ratings' => $this->getRatingResults(),
        ];
    }
    /**
     * @param array $results
     */
    private function createResults(array $results)
    {
        $resultsGen = Util::createGenerator($results);

        foreach ($resultsGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];

            if (is_numeric($key)) {
                $this->iterableResults[] = $item;
            } else if (is_string($key)) {
                $this->ratingResults[$key] = $item;
            }
        }
    }
}