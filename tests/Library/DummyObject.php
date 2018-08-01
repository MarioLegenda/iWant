<?php

namespace App\Tests\Library;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class DummyObject implements ArrayNotationInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var DummyObject $innerDummy
     */
    private $innerDummy;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    /**
     * @param DummyObject $object
     */
    public function setInnerDummy(DummyObject $object)
    {
        $this->innerDummy = $object;
    }
    /**
     * @return DummyObject
     */
    public function getInnerDummy(): DummyObject
    {
        return $this->innerDummy;
    }
    /**
     * @inheritdoc
     */
    public function toArray(): iterable
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'innerDummy' => ($this->innerDummy instanceof DummyObject) ? $this->getInnerDummy()->toArray() : null,
        ];
    }
}