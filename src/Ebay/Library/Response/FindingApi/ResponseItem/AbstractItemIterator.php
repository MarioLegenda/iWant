<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

class AbstractItemIterator extends AbstractItem implements
    \IteratorAggregate,
    \Countable,
    \ArrayAccess
{
    /**
     * @var int $position
     */
    protected $position = 0;
    /**
     * @var array $items
     */
    protected $items = array();
    /**
     * @param ResponseItemInterface $item
     * @return ResponseItemInterface
     */
    public function addItem(ResponseItemInterface $item) : ResponseItemInterface
    {
        $this->items[] = $item;

        return $this;
    }
    /**
     * @param int $position
     * @return mixed|null
     */
    public function getItemByPosition(int $position)
    {
        if ($this->hasItem($position)) {
            return null;
        }

        return $this->items[$position];
    }
    /**
     * @param $key
     * @return bool
     */
    public function hasItem($key) : bool
    {
        return array_key_exists($key, $this->items);
    }
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }
    /**
     * @return int|mixed
     */
    public function count()
    {
        return count($this->items);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }
    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
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
}