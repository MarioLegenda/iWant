<?php

namespace App\Tests\FindingApi\Unit;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\Information\ISO3166CountryCodeInformation;
use App\Ebay\Library\ItemFilter\AuthorizedSellerOnly;
use App\Ebay\Library\ItemFilter\AvailableTo;
use App\Ebay\Library\ItemFilter\BestOfferOnly;
use App\Ebay\Library\ItemFilter\CharityOnly;
use App\Ebay\Library\ItemFilter\Condition;
use App\Ebay\Library\ItemFilter\ItemFilter;
use PHPUnit\Framework\TestCase;

class ItemFiltersTest extends TestCase
{
    public function test_authorized_seller_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::AUTHORIZED_SELLER_ONLY, $value);

            $authorizedSellerOnly = new AuthorizedSellerOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($authorizedSellerOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::AUTHORIZED_SELLER_ONLY, ['invalid']);

        $authorizedSellerOnly = new AuthorizedSellerOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $authorizedSellerOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_available_to()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $codes = ISO3166CountryCodeInformation::instance()->getAll();

        foreach ($codes as $code) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::AVAILABLE_TO, [$code['alpha2']]);

            $availableTo = new AvailableTo(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($availableTo->validateDynamic());
        }

        $entersInvalidException = false;
        try {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::AVAILABLE_TO, ['invalid']);

            $availableTo = new AvailableTo(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $availableTo->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_best_offer_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::BEST_OFFER_ONLY, $value);

            $bestOfferOnly = new BestOfferOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($bestOfferOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::BEST_OFFER_ONLY, ['invalid']);

        $bestOfferOnly = new BestOfferOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $bestOfferOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_charity_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::CHARITY_ONLY, $value);
            $charityOnly = new CharityOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($charityOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::CHARITY_ONLY, ['invalid']);

        $charityOnly = new CharityOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $charityOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_condition()
    {
        $value = ['New', 1000, 1500];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::CONDITION, $value);

        $condition = new Condition(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($condition->validateDynamic());

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::CONDITION, ['Invalid']);

        $condition = new Condition(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $condition->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }
    /**
     * @param string $name
     * @param array $value
     * @return DynamicMetadata
     */
    private function getDynamicMetadata(
        string $name,
        array $value
    ): DynamicMetadata {
        return new DynamicMetadata(
            $name,
            $value
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