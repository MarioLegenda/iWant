<?php

namespace App\Ebay\Library\Information;

use App\Library\Information\InformationInterface;

class ListingTypeInformation implements InformationInterface
{
    const ALL = 'All';
    const AUCTION = 'Auction';
    const AUCTION_WITH_BIN = 'AuctionWithBIN';
    const CLASSIFIED = 'Classified';
    const FIXED_PRICE = 'FixedPrice';
    const STORE_INVENTORY = 'StoreInventory';
    /**
     * @var array $listingTypes
     */
    private $listingTypes = array(
        'All',
        'Auction',
        'AuctionWithBIN',
        'Classified',
        'FixedPrice',
        'StoreInventory'
    );
    /**
     * @var ListingTypeInformation $instance
     */
    private static $instance;
    /**
     * @return ListingTypeInformation
     */
    public static function instance()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }
    /**
     * @param string $method
     * @return mixed
     */
    public function has(string $method) : bool
    {
        return in_array($method, $this->listingTypes) !== false;
    }
    /**
     * @param string $method
     * @return ListingTypeInformation
     */
    public function add(string $method)
    {
        if ($this->has($method)) {
            return null;
        }

        $this->listingTypes[] = $method;

        return $this;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->listingTypes);

        if (array_key_exists($position, $this->listingTypes)) {
            unset($this->listingTypes[$position]);

            return true;
        }

        return false;
    }
    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->listingTypes;
    }
}