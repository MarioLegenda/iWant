<?php

namespace App\Ebay\Presentation\FindingApi\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Query implements ArrayNotationInterface
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $value
     */
    private $value;
    /**
     * Query constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(
        string $name,
        string $value
    ) {
        $this->name = $name;
        $this->value = $value;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

}