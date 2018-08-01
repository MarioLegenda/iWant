<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Error\ErrorMessage;

class ErrorContainer extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * ErrorContainer constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadErrors($simpleXML);
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
     * @param \SimpleXMLElement $simpleXml
     */
    public function loadErrors(\SimpleXMLElement $simpleXml)
    {
        foreach ($simpleXml as $errorMessage) {
            $this->addItem(new ErrorMessage($errorMessage));
        }
    }
}