<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Aspect\Aspect;

class AspectHistogramContainer extends AbstractItemIterator implements ArrayNotationInterface, \JsonSerializable
{
    /**
     * @var string $domainDisplayName
     */
    private $domainDisplayName;
    /**
     * ConditionHistogramContainer constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadAspects($simpleXML);
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getDomainDisplayName($default = null): ?string
    {
        if ($this->domainDisplayName === null) {
            if (!empty($this->simpleXml->domainDisplayName)) {
                $this->setDomainDisplayName((string) $this->simpleXml->domainDisplayName);
            }
        }

        if ($this->domainDisplayName === null and $default !== null) {
            return $default;
        }

        return $this->domainDisplayName;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        $toArray['domainDisplayName'] = $this->getDomainDisplayName();

        $toArray['aspects'] = array();

        foreach ($this->items as $item) {
            $toArray['aspects'][] = $item->toArray();
        }

        return $toArray;
    }
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    /**
     * @param string $domainDisplayName
     */
    private function setDomainDisplayName(string $domainDisplayName)
    {
        $this->domainDisplayName = $domainDisplayName;
    }
    /**
     * @param \SimpleXMLElement $simpleXml
     */
    private function loadAspects(\SimpleXMLElement $simpleXml)
    {
        if (!empty($simpleXml->aspect)) {
            foreach ($simpleXml->aspect as $aspect) {
                $this->addItem(new Aspect($aspect));
            }
        }
    }
}