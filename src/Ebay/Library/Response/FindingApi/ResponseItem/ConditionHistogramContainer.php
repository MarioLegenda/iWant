<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\ConditionHistogram\ConditionHistogram;

class ConditionHistogramContainer extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * ConditionHistogramContainer constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadContainer($simpleXML);
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        foreach ($this->items as $item) {
            $toArray[] = $item->toArray();
        }

        return $toArray;
    }
    /**
     * @param \SimpleXMLElement $simpleXMLElement
     */
    private function loadContainer(\SimpleXMLElement $simpleXMLElement)
    {
        foreach ($simpleXMLElement->conditionHistogram as $conditionHistogram) {
            $this->addItem(new ConditionHistogram($conditionHistogram));
        }
    }
}