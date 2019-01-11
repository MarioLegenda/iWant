<?php

namespace App\Symfony\Facade;

use Indragunawan\FacadeBundle\AbstractFacade;

class CountryRepresentation extends AbstractFacade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Library\Representation\CountryRepresentation::class;
    }
}