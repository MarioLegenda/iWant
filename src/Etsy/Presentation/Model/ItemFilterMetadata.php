<?php

namespace App\Etsy\Presentation\Model;

use App\Library\Infrastructure\Type\TypeInterface;

class ItemFilterMetadata
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var array $value
     */
    private $value;
    /**
     * ItemFilterModel constructor.
     * @param TypeInterface $name
     * @param array $value
     */
    public function __construct(
        TypeInterface $name,
        array $value
    ) {
        $this->name = $name;
        $this->value = $value;
    }
    /**
     * @return TypeInterface
     */
    public function getName(): TypeInterface
    {
        return $this->name;
    }
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }
}