<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\ConditionHistogram;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\ResponseItem\AbstractItem;

class ConditionHistogram extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $conditionDisplayName
     */
    private $conditionDisplayName;
    /**
     * @var int $conditionId
     */
    private $conditionId;
    /**
     * @var int $count
     */
    private $count;
    /**
     * @param null $default
     * @return null
     */
    public function getConditionDisplayName($default = null)
    {
        if ($this->conditionDisplayName === null) {
            if (!empty($this->simpleXml->condition->conditionDisplayName)) {
                $this->setConditionDisplayName((string) $this->simpleXml->condition->conditionDisplayName);
            }
        }

        if ($this->conditionDisplayName === null and $default !== null) {
            return $default;
        }

        return $this->conditionDisplayName;
    }
    /**
     * @param null $default
     * @return int
     */
    public function getConditionId($default = null)
    {
        if ($this->conditionId === null) {
            if (!empty($this->simpleXml->condition->conditionId)) {
                $this->setConditionId((int) $this->simpleXml->condition->conditionId);
            }
        }

        if ($this->conditionId === null and $default !== null) {
            return $default;
        }

        return $this->conditionId;
    }
    /**
     * @param null $default
     * @return int|null
     */
    public function getCount($default = null)
    {
        if ($this->count === null) {
            if (!empty($this->simpleXml->count)) {
                $this->setCount((int) $this->simpleXml->count);
            }
        }

        if ($this->count === null and $default !== null) {
            return $default;
        }

        return $this->count;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'conditionId' => $this->getConditionId(),
            'conditionDisplayName' => $this->getConditionDisplayName(),
            'count' => $this->getCount(),
        );
    }

    private function setCount(int $count) : ConditionHistogram
    {
        $this->count = $count;

        return $this;
    }

    private function setConditionId(int $conditionId) : ConditionHistogram
    {
        $this->conditionId = $conditionId;

        return $this;
    }

    private function setConditionDisplayName(string $conditionDisplayName) : ConditionHistogram
    {
        $this->conditionDisplayName = $conditionDisplayName;

        return $this;
    }
}