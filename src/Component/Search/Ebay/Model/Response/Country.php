<?php

namespace App\Component\Search\Ebay\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Country implements ArrayNotationInterface
{
    /**
     * @var bool $isAvailable
     */
    private $isAvailable = true;
    /**
     * @var array $countryInfo
     */
    private $countryInfo = [];
    /**
     * Country constructor.
     * @param array|null|iterable $countryInfo
     */
    public function __construct(array $countryInfo = null)
    {
        if (!is_array($countryInfo)) {
            $this->isAvailable = false;

            return null;
        }

        $this->countryInfo = $countryInfo;
    }

    public function toArray(): iterable
    {
        return (empty($this->countryInfo)) ? [] : $this->countryInfo;
    }
}