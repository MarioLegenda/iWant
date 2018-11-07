<?php

namespace App\App\Presentation\Model\Request;

class SingleItemRequestModel
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * SingleItemRequestModel constructor.
     * @param string $itemId
     */
    public function __construct(
        string $itemId
    ) {
        $this->itemId = $itemId;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
}