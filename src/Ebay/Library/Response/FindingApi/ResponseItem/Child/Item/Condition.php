<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class Condition extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var int $conditionId
     */
    private $conditionId;
    /**
     * @var string $conditionDisplayName
     */
    private $conditionDisplayName;
    /**
     * @param mixed $default
     * @return int|null
     */
    public function getConditionId($default = null)
    {
        if ($this->conditionId === null) {
            if (!empty($this->simpleXml->conditionId)) {
                $this->setConditionId((int) $this->simpleXml->conditionId);
            }
        }

        if ($this->conditionId === null and $default !== null) {
            return $default;
        }

        return $this->conditionId;
    }
    /**
     * @param mixed $default
     * @return null|string
     */
    public function getConditionDisplayName($default = null)
    {
        if ($this->conditionDisplayName === null) {
            if (!empty($this->simpleXml->conditionDisplayName)) {
                $this->setConditionDisplayName((string) $this->simpleXml->conditionDisplayName);
            }
        }

        if ($this->conditionDisplayName === null and $default !== null) {
            return $default;
        }

        return $this->conditionDisplayName;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'conditionId' => $this->getConditionId(),
            'conditionDisplayName' => $this->getConditionDisplayName(),
        );
    }

    private function setConditionId(int $conditionId) : Condition
    {
        $this->conditionId = $conditionId;

        return $this;
    }

    private function setConditionDisplayName(string $conditionDisplayName) : Condition
    {
        $this->conditionDisplayName = $conditionDisplayName;

        return $this;
    }
}