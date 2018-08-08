<?php

namespace App\Etsy\Library\Information;

use App\Library\Information\InformationInterface;

class SortOrderInformation implements InformationInterface
{
    const UP = 'Up';
    const DOWN = 'Down';
    /**
     * @var array $sortOn
     */
    private $sortOrder = [
        'Up',
        'Down',
    ];
    /**
     * @var SortOrderInformation $instance
     */
    private static $instance;
    /**
     * @return SortOrderInformation
     */
    public static function instance()
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * @param string $sortOrder
     * @return mixed
     */
    public function has(string $sortOrder) : bool
    {
        return in_array($sortOrder, $this->sortOrder) !== false;
    }
    /**
     * @param string $sortOrder
     * @return InformationInterface
     */
    public function add(string $sortOrder) : InformationInterface
    {
        if ($this->has($sortOrder)) {
            return null;
        }

        $this->sortOrder[] = $sortOrder;

        return self::$instance;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->sortOrder);

        if (array_key_exists($position, $this->sortOrder)) {
            unset($this->sortOrder[$position]);

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->sortOrder;
    }
}