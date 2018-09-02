<?php

namespace App\Ebay\Presentation\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ItemFilter implements ArrayNotationInterface
{
    /**
     * @var ItemFilterMetadata $metadata
     */
    private $metadata;
    /**
     * ItemFilter constructor.
     * @param ItemFilterMetadata $metadata
     */
    public function __construct(
        ItemFilterMetadata $metadata
    ) {
        $this->metadata = $metadata;
    }
    /**
     * @return ItemFilterMetadata
     */
    public function getItemFilterMetadata(): ItemFilterMetadata
    {
        return $this->metadata;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return $this->getItemFilterMetadata()->toArray();
    }
}