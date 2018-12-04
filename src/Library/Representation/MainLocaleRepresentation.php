<?php

namespace App\Library\Representation;

class MainLocaleRepresentation
{
    /**
     * @var string $mainLocale
     */
    private $mainLocale;
    /**
     * MainLocaleRepresentation constructor.
     * @param string $mainLocale
     */
    public function __construct(string $mainLocale)
    {
        $this->mainLocale = $mainLocale;
    }
    /**
     * @return string
     */
    public function getMainLocale(): string
    {
        return $this->mainLocale;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->mainLocale;
    }
}