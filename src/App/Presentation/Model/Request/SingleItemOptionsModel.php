<?php

namespace App\App\Presentation\Model\Request;

class SingleItemOptionsModel
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * SingleItemOptionsModel constructor.
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