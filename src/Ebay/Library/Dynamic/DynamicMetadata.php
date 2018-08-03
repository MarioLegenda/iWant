<?php

namespace App\Ebay\Library\Dynamic;

class DynamicMetadata
{
    /**
     * @var string $name
     */
    protected $name;
    /**
     * @var array $dynamicValue
     */
    protected $dynamicValue;
    /**
     * @var string|null $paramName
     */
    protected $paramName;
    /**
     * @var string|null $paramValue
     */
    protected $paramValue;
    /**
     * @param string $name
     * @param array $dynamicValue
     * @param string|null $paramName
     * @param string|null $paramValue
     */
    public function __construct(
        string $name,
        array $dynamicValue,
        string $paramName = null,
        string $paramValue = null
    ) {
        $this->name = $name;
        $this->dynamicValue = $dynamicValue;
        $this->paramName = $paramName;
        $this->paramValue = $paramValue;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return array
     */
    public function getDynamicValue(): array
    {
        return $this->dynamicValue;
    }
    /**
     * @return null|string
     */
    public function getParamName(): ?string
    {
        return $this->paramName;
    }
    /**
     * @return null|string
     */
    public function getParamValue(): ?string
    {
        return $this->paramValue;
    }
    /**
     * @return bool
     */
    public function hasParamOption(): bool
    {
        return is_string($this->getParamName()) and is_string($this->getParamValue());
    }
}