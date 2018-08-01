<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Aspect;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\ResponseItem\AbstractItemIterator;

class Aspect extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * @var string $aspectName
     */
    private $aspectName;
    /**
     * @param null $default
     * @return null|string
     */
    public function getAspectName($default = null)
    {
        if ($this->aspectName === null) {
            if (!empty($this->simpleXml['name'])) {
                $this->setAspectName((string) $this->simpleXml['name']);
            }
        }

        if ($this->aspectName === null and $default !== null) {
            return $default;
        }

        return $this->aspectName;
    }
    /**
     * @param null|mixed $default
     */
    public function getValuesHistograms($default = null)
    {
        if ($this->isEmpty()) {
            $this->loadValueHistograms($this->simpleXml);
        }

        if ($this->isEmpty() and $default !== null) {
            return $default;
        }

        if (!$this->isEmpty()) {
            return $this->items;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        $toArray['aspectName'] = $this->getAspectName();

        $toArray['valueHistograms'] = array();

        $this->getValuesHistograms();

        foreach ($this->items as $item) {
            $toArray['valueHistograms'][] = $item->toArray();
        }

        return $toArray;
    }


    private function loadValueHistograms(\SimpleXMLElement $simpleXml)
    {
        if (!empty($simpleXml->valueHistogram)) {
            foreach ($simpleXml->valueHistogram as $valueHistogram) {
                $this->setValueHistogram(new ValueHistogram($valueHistogram));
            }
        }
    }

    private function setValueHistogram(ValueHistogram $valueHistogram) : Aspect
    {
        $this->addItem($valueHistogram);

        return $this;
    }

    private function setAspectName(string $aspectName) : Aspect
    {
        $this->aspectName = $aspectName;

        return $this;
    }
}