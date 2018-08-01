<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\CategoryHistogram\CategoryHistogram;

class CategoryHistogramContainer extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * CategoryHistogramContainer constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadCategoryHistograms($simpleXML);
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

    private function loadCategoryHistograms(\SimpleXMLElement $simpleXml)
    {
        foreach ($simpleXml->categoryHistogram as $categoryHistogram) {
            $this->addItem(new CategoryHistogram($categoryHistogram));
        }
    }
}