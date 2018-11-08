<?php

namespace App\App\Presentation\Model\Request;

class SingleItemRequestModel
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var string $locale
     */
    private $locale;
    /**
     * SingleItemRequestModel constructor.
     * @param string $itemId
     * @param string $locale
     */
    public function __construct(
        string $itemId,
        string $locale
    ) {
        $this->itemId = $itemId;
        $this->locale = $locale;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}