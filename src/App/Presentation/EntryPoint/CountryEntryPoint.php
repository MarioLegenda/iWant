<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Finder;
use App\Library\Infrastructure\Helper\TypedArray;

class CountryEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * CountryEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @return TypedArray
     */
    public function getCountries(): TypedArray
    {
        return $this->finder->getCountries();
    }
}