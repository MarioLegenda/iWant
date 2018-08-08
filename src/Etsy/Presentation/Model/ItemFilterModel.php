<?php

namespace App\Etsy\Presentation\Model;

class ItemFilterModel
{
    /**
     * @var ItemFilterMetadata $itemFilterMetadata
     */
    private $itemFilterMetadata;
    /**
     * ItemFilterModel constructor.
     * @param ItemFilterMetadata $itemFilterMetadata
     */
    public function __construct(
        ItemFilterMetadata $itemFilterMetadata
    ) {
        $this->itemFilterMetadata = $itemFilterMetadata;
    }
    /**
     * @return ItemFilterMetadata
     */
    public function getItemFilterMetadata(): ItemFilterMetadata
    {
        return $this->itemFilterMetadata;
    }
}