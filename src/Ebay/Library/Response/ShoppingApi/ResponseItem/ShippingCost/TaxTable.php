<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem\ShippingCost;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\AbstractItemIterator;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class TaxTable extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * CategoryHistogram constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        if (!empty($simpleXML->TaxTable)) {
            $this->loadTaxJurisdictions($simpleXML);
        }
    }
    /**
     * @param \SimpleXMLElement $simpleXml
     */
    private function loadTaxJurisdictions(\SimpleXMLElement $simpleXml)
    {
        foreach ($simpleXml as $taxJurisdiction) {
            $this->addItem(new TaxJurisdiction($taxJurisdiction));
        }
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
        ];
    }
}