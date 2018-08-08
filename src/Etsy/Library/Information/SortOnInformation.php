<?php

namespace App\Etsy\Library\Information;

use App\Library\Information\InformationInterface;

class SortOnInformation implements InformationInterface
{
    const CREATED = 'Created';
    const PRICE = 'Price';
    const SCORE = 'Score';
    /**
     * @var array $sortOn
     */
    private $sortOn = [
        'Created',
        'Price',
        'Score',
    ];
    /**
     * @var SortOnInformation $instance
     */
    private static $instance;
    /**
     * @return SortOnInformation
     */
    public static function instance()
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * @param string $sortOn
     * @return mixed
     */
    public function has(string $sortOn) : bool
    {
        return in_array($sortOn, $this->sortOn) !== false;
    }
    /**
     * @param string $sortOn
     * @return InformationInterface
     */
    public function add(string $sortOn) : InformationInterface
    {
        if ($this->has($sortOn)) {
            return null;
        }

        $this->sortOn[] = $sortOn;

        return self::$instance;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->sortOn);

        if (array_key_exists($position, $this->sortOn)) {
            unset($this->sortOn[$position]);

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->sortOn;
    }
}