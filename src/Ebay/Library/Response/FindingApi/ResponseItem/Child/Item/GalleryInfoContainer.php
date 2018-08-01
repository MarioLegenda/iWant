<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItemIterator;

class GalleryInfoContainer extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * GalleryInfoContainer constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadItems($simpleXML);
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

    private function loadItems(\SimpleXMLElement $simpleXml)
    {
        foreach ($simpleXml->galleryURL as $galleryUrl) {
            $this->addItem(new GalleryUrl($galleryUrl));
        }
    }
}