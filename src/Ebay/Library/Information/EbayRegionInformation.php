<?php

namespace App\Ebay\Library\Information;

use App\Library\Information\InformationInterface;

class EbayRegionInformation implements InformationInterface
{
    /**
     * @var EbayRegionInformation $instance
     */
    private static $instance;

    private $information = [
        'Alaska/Hawaii',
        'US Protectorates',
        'Africa',
        'Asia',
        'Central America and Caribbean',
        'Europe',
        'Middle East',
        'North America',
        'Oceania',
        'Southeast Asia',
        'South America',
    ];
    /**
     * @return EbayRegionInformation
     */
    public static function instance(): EbayRegionInformation
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->information;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function has(string $entry): bool
    {
        return in_array($entry, $this->information) === true;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $message = sprintf(
            '%s::%s is not implemented',
            get_class($this),
            __FUNCTION__
        );

        throw new \RuntimeException($message);
    }
}