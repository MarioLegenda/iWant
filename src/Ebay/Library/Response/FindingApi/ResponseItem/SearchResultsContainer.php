<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;

class SearchResultsContainer extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * SearchResultsContainer constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadItems($simpleXML);
    }
    /**
     * @param string $itemId
     * @return mixed
     */
    public function getItemById(string $itemId) : Item
    {
        foreach ($this->items as $item) {
            if ($item->getItemId() === $itemId) {
                return $item;
            }
        }
    }
    /**
     * @param string $name
     * @return null
     */
    public function getItemByName(string $name)
    {
        foreach ($this->items as $item) {
            if ($item->getTitle() === $name) {
                return $item;
            }
        }

        return null;
    }

    public function toArray(): array
    {
        $toArray = array();

        foreach ($this->items as $item) {
            $toArray[] = $item->toArray();
        }

        return $toArray;
    }

    private function loadItems(\SimpleXMLElement $simpleXml)
    {
        $items = $simpleXml->children();

        foreach ($items as $item) {
            $productItem = new Item($item);

            $this->addItem($productItem);
        }
    }
}