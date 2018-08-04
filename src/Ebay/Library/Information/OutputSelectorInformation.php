<?php

namespace App\Ebay\Library\Information;

use App\Library\Information\InformationInterface;

class OutputSelectorInformation implements InformationInterface
{
    const ASPECT_HISTOGRAM = 'AspectHistogram';
    const CATEGORY_HISTOGRAM = 'CategoryHistogram';
    const CONDITION_HISTOGRAM = 'ConditionHistogram';
    const GALLERY_INFO = 'GalleryInfo';
    const PICTURE_URL_LARGE = 'PictureURLLarge';
    const PICTURE_URL_SUPER_SIZE = 'PictureURLSuperSize';
    const SELLER_INFO = 'SellerInfo';
    const STORE_INFO = 'StoreInfo';
    const UNIT_PRICE_INFO = 'UnitPriceInfo';

    /**
     * @var array $outputSelectors
     */
    private $outputSelectors = array(
        'AspectHistogram',
        'CategoryHistogram',
        'ConditionHistogram',
        'GalleryInfo',
        'PictureURLLarge',
        'PictureURLSuperSize',
        'SellerInfo',
        'StoreInfo',
        'UnitPriceInfo',
    );
    /**
     * @var OutputSelectorInformation $instance
     */
    private static $instance;
    /**
     * @return OutputSelectorInformation
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
        return in_array($method, $this->outputSelectors) !== false;
    }
    /**
     * @param string $method
     * @return OutputSelectorInformation
     */
    public function add(string $method)
    {
        if ($this->has($method)) {
            return null;
        }

        $this->outputSelectors[] = $method;

        return $this;
    }
    /**
     * @param string $entry
     * @return bool
     */
    public function remove(string $entry): bool
    {
        $position = array_search($entry, $this->outputSelectors);

        if (array_key_exists($position, $this->outputSelectors)) {
            unset($this->outputSelectors[$position]);

            return true;
        }

        return false;
    }
    /**
     * @return array
     */
    public function getAll() : array
    {
        return $this->outputSelectors;
    }
}