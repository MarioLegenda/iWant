<?php

namespace App\Etsy\Library\Dynamic;

class DynamicConfiguration
{
    /**
     * @var boolean $multipleValues
     */
    private $multipleValues;
    /**
     * @var boolean $dateTime
     */
    private $dateTime;
    /**
     * DynamicConfiguration constructor.
     * @param bool $multipleValues
     * @param bool $dateTime
     */
    public function __construct(
        bool $multipleValues = false,
        bool $dateTime = false
    ) {
        $this->multipleValues = $multipleValues;
        $this->dateTime = $dateTime;
    }
    /**
     * @return bool
     */
    public function isMultipleValues(): bool
    {
        return $this->multipleValues;
    }
    /**
     * @return bool
     */
    public function isDateTime(): bool
    {
        return $this->dateTime;
    }
}