<?php

namespace App\Library\Representation;

use App\Doctrine\Repository\CountryRepository;

class CountryRepresentation
{
    /**
     * @var CountryRepresentation $instance
     */
    private static $instance;
    /**
     * @var CountryRepository
     */
    private $countryRepository;
    /**
     * CountryRepresentation constructor.
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        CountryRepository $countryRepository
    ) {
        $this->countryRepository = $countryRepository;

        static::$instance = $this;
    }
    /**
     * @return \Closure
     * @internal
     */
    public function exposeCountryRepository(): \Closure
    {
        return \Closure::fromCallable([$this, 'getCountryRepository']);
    }
    /**
     * @param string $code
     * @return array
     */
    public static function getByAlpha2Code(string $code): array
    {
        $c = static::$instance->exposeCountryRepository();

        return $c()->findOneBy([
            'alpha2Code' => $code
        ])->toArray();
    }
    /**
     * @return CountryRepository
     */
    private function getCountryRepository(): CountryRepository
    {
        return $this->countryRepository;
    }
}