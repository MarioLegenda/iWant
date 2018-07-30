<?php

namespace App\Ebay\Presentation\EntryPoint;

use App\Ebay\Business\Finder;

class FindingApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * FindingApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
}