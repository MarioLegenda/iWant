<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Condition implements ArrayNotationInterface
{
    /**
     * @var string|null
     */
    private $conditionId;
    /**
     * @var string|null
     */
    private $conditionDisplayName;
    /**
     * @var string|null
     */
    private $conditionDescription;
    /**
     * Condition constructor.
     * @param string $conditionId
     * @param string $conditionDisplayName
     * @param string $conditionDescription
     */
    public function __construct(
        ?string $conditionId,
        ?string $conditionDisplayName,
        ?string $conditionDescription
    ) {
        $this->conditionId = $conditionId;
        $this->conditionDisplayName = $conditionDisplayName;
        $this->conditionDescription = $conditionDescription;
    }
    /**
     * @return string|null
     */
    public function getConditionDescription(): ?string
    {
        return $this->conditionDescription;
    }
    /**
     * @return string|null
     */
    public function getConditionId(): ?string
    {
        return $this->conditionId;
    }
    /**
     * @return string|null
     */
    public function getConditionDisplayName(): ?string
    {
        return $this->conditionDisplayName;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'conditionId' => $this->getConditionId(),
            'conditionDisplayName' => $this->getConditionDisplayName(),
            'conditionDescription' => $this->getConditionDescription(),
        ];
    }
}