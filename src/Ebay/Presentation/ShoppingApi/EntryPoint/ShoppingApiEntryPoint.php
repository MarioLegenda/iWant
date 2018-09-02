<?php

namespace App\Ebay\Presentation\ShoppingApi\EntryPoint;

use App\Ebay\Business\Finder;

class ShoppingApiEntryPoint
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