<?php

namespace App\Yandex\Presentation\Model;

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
     * @param string|null $value
     */
    public function __construct(
        string $name,
        string $value = null
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
    public function getValue(): ?string
    {
        if (is_null($this->value)) {
            return '';
        }

        return $this->value;
    }
    /**
     * @return string
     */
    public function getQuery(): string
    {
        if (empty($this->getValue())) {
            return $this->getName();
        }

        return sprintf(
            '%s=%s',
            $this->getName(),
            $this->getValue()
        );
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