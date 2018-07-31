<?php

namespace App\Ebay\Presentation\Model;

class ItemFilter
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
}