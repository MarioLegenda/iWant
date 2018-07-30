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
     * @param string $name
     * @param array $dynamicValue
     */
    public function __construct(
        string $name,
        array $dynamicValue
    ) {
        $this->name = $name;
        $this->dynamicValue = $dynamicValue;
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
}