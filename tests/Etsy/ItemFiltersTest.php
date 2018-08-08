<?php

namespace App\Tests\Etsy;

use App\Etsy\Library\Information\SortOnInformation;
use App\Etsy\Library\Dynamic\DynamicMetadata;
use App\Etsy\Library\Dynamic\DynamicConfiguration;
use App\Etsy\Library\Dynamic\DynamicErrors;
use App\Etsy\Library\ItemFilter\ItemFilter;
use App\Etsy\Library\ItemFilter\Keywords;
use App\Etsy\Library\ItemFilter\Limit;
use App\Etsy\Library\ItemFilter\MaxPrice;
use App\Etsy\Library\ItemFilter\MinPrice;
use App\Etsy\Library\ItemFilter\Offset;
use App\Etsy\Library\ItemFilter\Page;
use App\Etsy\Library\ItemFilter\SortOn;
use App\Tests\Library\BasicSetup;

class ItemFiltersTest extends BasicSetup
{
    public function test_keywords_item_filter()
    {
        $value = 'boots, mountain';

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::KEYWORDS,
            [$value]
        );
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $itemFilter = new Keywords(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $value = 0;
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::KEYWORDS,
                [$value]
            );

            $itemFilter = new Keywords(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_limit_item_filter()
    {
        $value = 29;

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::LIMIT,
            [$value]
        );
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $itemFilter = new Limit(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $value = 'invalid';
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::LIMIT,
                [$value]
            );

            $itemFilter = new Limit(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $entersInvalidException = false;
        try {
            $value = -1;
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::LIMIT,
                [$value]
            );

            $itemFilter = new Limit(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_max_price_item_filter()
    {
        $value = 29.5;

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MAX_PRICE,
            [$value]
        );
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $itemFilter = new MaxPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $value = 'invalid';
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::MAX_PRICE,
                [$value]
            );

            $itemFilter = new MaxPrice(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $entersInvalidException = false;
        try {
            $value = -1;
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::MAX_PRICE,
                [$value]
            );

            $itemFilter = new MaxPrice(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_min_price_item_filter()
    {
        $value = 29.7;

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MIN_PRICE,
            [$value]
        );
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $itemFilter = new MinPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $value = 'invalid';
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::MIN_PRICE,
                [$value]
            );

            $itemFilter = new MinPrice(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $entersInvalidException = false;
        try {
            $value = -1;
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::MIN_PRICE,
                [$value]
            );

            $itemFilter = new MinPrice(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_offset_item_filter()
    {
        $value = 29;

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::OFFSET,
            [$value]
        );
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $itemFilter = new Offset(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $value = 'invalid';
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::OFFSET,
                [$value]
            );

            $itemFilter = new Offset(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $entersInvalidException = false;
        try {
            $value = -1;
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::OFFSET,
                [$value]
            );

            $itemFilter = new Offset(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_page_item_filter()
    {
        $value = 29;

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::PAGE,
            [$value]
        );
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $itemFilter = new Page(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $value = -1;
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::PAGE,
                [$value]
            );

            $itemFilter = new Page(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $entersInvalidException = false;
        try {
            $value = 'invalid';
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::PAGE,
                [$value]
            );

            $itemFilter = new Page(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();

        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_sort_on_item_filter()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $sortOnInformation = SortOnInformation::instance()->getAll();

        foreach ($sortOnInformation as $item) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::SORT_ON,
                [$item]
            );

            $itemFilter = new SortOn(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($itemFilter->validateDynamic());
        }

        $entersInvalidException = false;
        try {
            $value = 'invalid';
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::SORT_ON,
                [$value]
            );

            $itemFilter = new SortOn(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $itemFilter->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }
    /**
     * @param string $name
     * @param array $value
     * @param string|null $paramName
     * @param string|null $paramValue
     * @return DynamicMetadata
     */
    private function getDynamicMetadata(
        string $name,
        array $value,
        string $paramName = null,
        string $paramValue = null
    ): DynamicMetadata {
        return new DynamicMetadata(
            $name,
            $value,
            $paramName,
            $paramValue
        );
    }
    /**
     * @param bool $multipleValues
     * @param bool $dateTime
     * @return DynamicConfiguration
     */
    private function getDynamicConfiguration(
        bool $multipleValues,
        bool $dateTime
    ): DynamicConfiguration {
        return new DynamicConfiguration($multipleValues, $dateTime);
    }
    /**
     * @return DynamicErrors
     */
    private function getDynamicErrors(): DynamicErrors
    {
        return new DynamicErrors();
    }
}