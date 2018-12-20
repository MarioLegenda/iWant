<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

abstract class AbstractItem implements ResponseItemInterface
{
    /**
     * @var $simpleXml
     */
    protected $simpleXml;
    /**
     * @var string $itemName
     */
    protected $itemName;
    /**
     * RootItem constructor.
     * @param \SimpleXmlElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        $this->itemName = $simpleXML->getName();
        $this->simpleXml = $simpleXML;
    }
    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->itemName;
    }
    /**
     * @return \SimpleXMLElement
     */
    public function getSimpleXml() 
    {
        return $this->simpleXml;
    }
}