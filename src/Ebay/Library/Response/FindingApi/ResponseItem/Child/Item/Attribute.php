<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\ResponseItem\AbstractItem;

class Attribute extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $attributeName
     */
    private $attributeName;
    /**
     * @var string $attributeValue
     */
    private $attributeValue;
    /**
     * @param null $default
     * @return null|string
     */
    public function getAttributeName($default = null)
    {
        if ($this->attributeName === null) {
            if (!empty($this->simpleXml->name)) {
                $this->setAttributeName((string) $this->simpleXml->name);
            }
        }

        if ($this->attributeName === null and $default !== null) {
            return $default;
        }

        return $this->attributeName;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getAttributeValue($default = null)
    {
        if ($this->attributeValue === null) {
            if (!empty($this->simpleXml->value)) {
                $this->setAttributeValue((string) $this->simpleXml->value);
            }
        }

        if ($this->attributeValue === null and $default !== null) {
            return $default;
        }

        return $this->attributeValue;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'attributeName' => $this->getAttributeName(),
            'attributeValue' => $this->getAttributeValue(),
        );
    }

    private function setAttributeValue(string $attributeValue) : Attribute
    {
        $this->attributeValue = $attributeValue;

        return $this;
    }

    private function setAttributeName(string $attributeName) : Attribute
    {
        $this->attributeName = $attributeName;

        return $this;
    }
}