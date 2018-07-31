<?php

namespace App\Ebay\Presentation\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ItemFilterMetadata implements ArrayNotationInterface
{
    /**
     * @var string $queryName
     */
    private $queryName;
    /**
     * @var string $valueName
     */
    private $valueName;
    /**
     * @var string $nameValue
     */
    private $nameValue;
    /**
     * @var iterable $valueValues
     */
    private $valueValues;

    public function __construct(
        string $queryName,
        string $valueName,
        string $nameValue,
        iterable $valueValues
    ) {
        $this->queryName = $queryName;
        $this->valueName = $valueName;
        $this->nameValue = $nameValue;
        $this->valueValues = $valueValues;
    }
    /**
     * @return string
     */
    public function getQueryName(): string
    {
        return $this->queryName;
    }
    /**
     * @return string
     */
    public function getValueName(): string
    {
        return $this->valueName;
    }
    /**
     * @return string
     */
    public function getNameValue(): string
    {
        return $this->nameValue;
    }
    /**
     * @return iterable
     */
    public function getValueValues(): iterable
    {
        return $this->valueValues;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'queryName' => $this->getQueryName(),
            'queryValue' => $this->getValueName(),
            'name' => $this->getNameValue(),
            'value' => $this->getValueValues(),
        ];
    }
}