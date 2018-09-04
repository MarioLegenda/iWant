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

        /** @var Category $item */
        foreach ($this->items as $item) {
            $toArray[] = $item->toArray();
        }

        return $toArray;
    }
    /**
     * @param \SimpleXMLElement $simpleXml
     */
    private function loadCategoryHistograms(\SimpleXMLElement $simpleXml)
    {
        foreach ($simpleXml->CategoryArray->Category as $category) {
            if ((string) $category->CategoryName !== 'Root') {
                $this->addItem(new Category($category));
            }
        }
    }
}