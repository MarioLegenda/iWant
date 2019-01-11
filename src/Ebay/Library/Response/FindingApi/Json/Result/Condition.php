<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Condition implements ArrayNotationInterface
{
    /**
     * @var string
     */
    private $conditionId;
    /**
     * @var string
     */
    private $conditionDisplayName;

    /**
     * Condition constructor.
     * @param string $conditionId
     * @param string $conditionDisplayName
     */
    public function __construct(string $conditionId, string $conditionDisplayName)
    {
        $this->conditionId = $conditionId;
        $this->conditionDisplayName = $conditionDisplayName;
    }
    /**
     * @return string
     */
    public function getConditionId(): string
    {
        return $this->conditionId;
    }
    /**
     * @return string
     */
    public function getConditionDisplayName(): string
    {
        return $this->conditionDisplayName;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return (array) $this;
    }
}