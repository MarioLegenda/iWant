<?php

namespace App\Bonanza\Business;

use App\Bonanza\Source\FinderSource;

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