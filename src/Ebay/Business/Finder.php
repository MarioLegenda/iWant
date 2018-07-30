<?php

namespace App\Ebay\Business;

use App\Ebay\Source\FinderSource;

class Finder
{
    /**
     * @var FinderSource $finderSource
     */
    private $finderSource;
    /**
     * Finder constructor.
     * @param FinderSource $finderSource
     */
    public function __construct(
        FinderSource $finderSource
    ) {
        $this->finderSource = $finderSource;
    }
}