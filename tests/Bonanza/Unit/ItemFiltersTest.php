<?php

namespace App\Tests\Bonanza\Unit;

use App\Bonanza\Library\Dynamic\DynamicConfiguration;
use App\Bonanza\Library\Dynamic\DynamicErrors;
use App\Bonanza\Library\Dynamic\DynamicMetadata;
use App\Bonanza\Library\Information\SortOrderInformation;
use App\Bonanza\Library\ItemFilter\BuyerPostalCode;
use App\Bonanza\Library\ItemFilter\ItemFilter;
use App\Bonanza\Library\ItemFilter\Keywords;
use App\Bonanza\Library\ItemFilter\PaginationInput;
use App\Bonanza\Library\ItemFilter\SortOrder;
use App\Tests\Library\BasicSetup;

class ItemFiltersTest extends BasicSetup
{
    public function test_buyer_postal_code_item_filter()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $value = '34F';

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::BUYER_POSTAL_CODE,
            [$value]
        );

        $itemFilter = new BuyerPostalCode(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($itemFilter->validateDynamic());

        $entersInvalidException = false;
        try {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::BUYER_POSTAL_CODE,
                [13]
            );

            $itemFilter = new BuyerPostalCode(
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

    public function test_sort_order_item_filter()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = SortOrderInformation::instance()->getAll();

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::SORT_ORDER,
                [$value]
            );

            $itemFilter = new SortOrder(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($itemFilter->validateDynamic());
        }

        $entersInvalidException = false;
        try {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::SORT_ORDER,
                ['invalid']
            );

            $itemFilter = new SortOrder(
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

    public function test_pagination_input()
    {
        $value = [
            'entriesPerPage' => 1,
            'pageNumber' => 4
        ];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::PAGINATION_INPUT,
            [$value]
        );

        $paginationInput = new PaginationInput(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($paginationInput->validateDynamic());

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::PAGINATION_INPUT,
            ['invalid']
        );

        $paginationInput = new PaginationInput(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $paginationInput->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::PAGINATION_INPUT,
            [
                [
                    'entriesPerPage' => 1,
                    'pageNumber' => 'invalid'
                ]
            ]
        );

        $paginationInput = new PaginationInput(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $paginationInput->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_keywords_item_filter()
    {
        $value = [
            'keywords' => 'boots, mountain'
        ];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::KEYWORDS,
            [$value]
        );

        $keywords = new Keywords(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($keywords->validateDynamic());

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::KEYWORDS,
            [5]
        );

        $keywords = new Keywords(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $keywords->validateDynamic();
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