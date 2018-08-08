<?php

namespace App\Amazon\Library\Response\ProductAdvertisingApi\ResponseItem;

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
    
    public function getSimpleXml() 
    {
        return $this->simpleXml;
    }
}