<?php

namespace App\Bonanza\Presentation;

use App\Bonanza\Business\Finder;

class BonanzaApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * BonanzaApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
}