<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class UnitPrice extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var float $quantity
     */
    private $quantity;
    /**
     * @var string $type
     */
    private $type;
    /**
     * @param null $default
     * @return float|null
     */
    public function getQuantity($default = null)
    {
        if ($this->quantity === null) {
            if (!empty($this->simpleXml->quantity)) {
                $this->setQuantity((float) $this->simpleXml->quantity);
            }
        }

        if ($this->quantity === null and $default !== null) {
            return $default;
        }

        return $this->quantity;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getType($default = null)
    {
        if ($this->type === null) {
            if (!empty($this->simpleXml->type)) {
                $this->setType((string) $this->simpleXml->type);
            }
        }

        if ($this->type === null and $default !== null) {
            return $default;
        }

        return $this->type;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'quantity' => $this->getQuantity(),
            'type' => $this->getType(),
        );
    }

    private function setQuantity(float $quantity) : UnitPrice
    {
        $this->quantity = $quantity;

        return $this;
    }

    private function setType(string $type) : UnitPrice
    {
        $this->type = $type;

        return $this;
    }
}