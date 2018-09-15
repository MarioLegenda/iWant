<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class NameValueList extends AbstractItemIterator
{
    /**
     * @var array $items
     */
    protected $items;
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
     * @param \SimpleXMLElement $simpleXml
     */
    private function loadItems(\SimpleXMLElement $simpleXml)
    {
        foreach ($simpleXml as $item) {
            $pairItem = [
                'name' => (string) $item->Name,
                'value' => (string) $item->Value,
            ];

            $this->items[] = $pairItem;
        }
    }
}