<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class Categories extends AbstractItemIterator
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
        foreach ($simpleXml->CategoryArray as $category) {
            $this->addItem(new Category($category->Category));
        }
    }
}