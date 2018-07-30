<?php

namespace App\Ebay\Library\Information;

class CurrencyInformation implements InformationInterface
{
    const AUSTRALIAN = 'AUD';
    const CANADIAN = 'CAD';
    const SWISS = 'CHF';
    const CHINESE = 'CNY';
    const EURO = 'EUR';
    const BRITISH = 'GBP';
    const HONG_KONG = 'HKD';
    const INDIAN = 'INR';
    const MALAYSIAN = 'MYR';
    const PHILIPPINES = 'PHP';
    const POLAND = 'PLN';
    const SWEDISH = 'SEK';
    const TAIWAN = 'TWD';
    const USA = 'USD';
    /**
     * @var array $currencies
     */
    private $currencies = array(
        'AUD',
        'CAD',
        'CHR',
        'CNY',
        'EUR',
        'GBP',
        'HKD',
        'INR',
        'MYR',
        'PHP',
        'PLN',
        'SEK',
        'TWD',
        'USD',
    );
    /**
     * @var CurrencyInformation $instance
     */
    private static $instance;
    /**
     * @return CurrencyInformation
     */
    public static function instance()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }
    /**
     * @param string $currency
     * @return mixed
     */
    public function has(string $currency) : bool
    {
        return in_array($currency, $this->currencies) !== false;
    }
    /**
     * @param $currency
     * @return InformationInterface
     */
    public function add(string $currency) : InformationInterface
    {
        if ($this->has($currency)) {
            return null;
        }

        $this->currencies[] = $currency;

        return self::$instance;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->currencies);

        if (array_key_exists($position, $this->currencies)) {
            unset($this->currencies[$position]);

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->currencies;
    }
}