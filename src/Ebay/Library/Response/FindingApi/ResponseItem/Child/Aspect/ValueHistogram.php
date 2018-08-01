<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Aspect;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\ResponseItem\AbstractItem;

class ValueHistogram extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $valueName
     */
    private $valueName;
    /**
     * @var int $count
     */
    private $count;
    /**
     * @param null $default
     * @return null|string
     */
    public function getValueName($default = null)
    {
        if ($this->valueName === null) {
            if (!empty($this->simpleXml['valueName'])) {
                $this->setValueName((string) $this->simpleXml['valueName']);
            }
        }

        if ($this->valueName === null and $default !== null) {
            return $default;
        }

        return $this->valueName;
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
            'valueName' => $this->getValueName(),
            'count' => $this->getCount(),
        );
    }

    private function setCount(int $count) : ValueHistogram
    {
        $this->count = $count;

        return $this;
    }

    private function setValueName(string $valueName) : ValueHistogram
    {
        $this->valueName = $valueName;

        return $this;
    }
}