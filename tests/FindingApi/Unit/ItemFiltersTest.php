<?php

namespace App\Tests\FindingApi\Unit;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\ItemFilter\AuthorizedSellerOnly;
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