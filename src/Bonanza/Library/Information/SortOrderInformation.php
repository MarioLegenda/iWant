<?php

namespace App\Bonanza\Library\Information;

use App\Library\Information\InformationInterface;

class SortOrderInformation implements InformationInterface
{
    const BEST_MATCH = 'BestMatch';
    const CURRENT_PRICE_HIGHEST = 'CurrentPriceHighest';
    const CURRENT_PRICE_LOWEST = 'CurrentPriceLowest';
    const START_TIME_NEWEST = 'StartTimeNewest';
    /**
     * @var array $sortOrders
     */
    private $sortOrders = array(
        'BestMatch',
        'CurrentPriceHighest',
        'CurrentPriceLowest',
        'StartTimeNewest',
    );
    /**
     * @var SortOrderInformation $instance
     */
    private static $instance;
    /**
     * @return SortOrderInformation
     */
    public static function instance()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }
    /**
     * @param string $sort
     * @return mixed
     */
    public function has(string $sort) : bool
    {
        return in_array($sort, $this->sortOrders) !== false;
    }
    /**
     * @param string $sort
     * @return SortOrderInformation
     */
    public function add(string $sort)
    {
        if ($this->has($sort)) {
            return null;
        }

        $this->sortOrders[] = $sort;

        return $this;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->sortOrders);

        if (array_key_exists($position, $this->sortOrders)) {
            unset($this->sortOrders[$position]);

            return true;
        }

        return false;
    }
    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->sortOrders;
    }
}