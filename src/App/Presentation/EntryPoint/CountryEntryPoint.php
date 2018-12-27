<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Finder;
use App\Doctrine\Entity\Country;
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
    /**
     * @param string $code
     * @return Country|null
     */
    public function findByAlpha2Code(string $code): ?Country
    {
        return $this->finder->findByAlpha2Code($code);
    }
}