<?php

namespace App\Translation\Model;

class Language implements TranslatedEntryInterface
{
    /**
     * @var string $entry
     */
    private $entry;
    /**
     * Language constructor.
     * @param string $entry
     */
    public function __construct(string $entry)
    {
        $this->entry = strToLowerWithEncoding(trim($entry));
    }
    /**
     * @return string
     */
    public function getEntry(): string
    {
        return $this->entry;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->entry;
    }
}