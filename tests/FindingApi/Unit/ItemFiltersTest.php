<?php

namespace App\Tests\FindingApi\Unit;

use App\Ebay\Library\Dynamic\DynamicConfiguration;
use App\Ebay\Library\Dynamic\DynamicErrors;
use App\Ebay\Library\Dynamic\DynamicMetadata;
use App\Ebay\Library\Information\CurrencyInformation;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Information\ISO3166CountryCodeInformation;
use App\Ebay\Library\Information\ListingTypeInformation;
use App\Ebay\Library\Information\SortOrderInformation;
use App\Ebay\Library\ItemFilter\AuthorizedSellerOnly;
use App\Ebay\Library\ItemFilter\AvailableTo;
use App\Ebay\Library\ItemFilter\BestOfferOnly;
use App\Ebay\Library\ItemFilter\CharityOnly;
use App\Ebay\Library\ItemFilter\Condition;
use App\Ebay\Library\ItemFilter\Currency;
use App\Ebay\Library\ItemFilter\EndTimeFrom;
use App\Ebay\Library\ItemFilter\EndTimeTo;
use App\Ebay\Library\ItemFilter\ExcludeAutoPay;
use App\Ebay\Library\ItemFilter\ExcludeCategory;
use App\Ebay\Library\ItemFilter\ExcludeSeller;
use App\Ebay\Library\ItemFilter\ExpeditedShippingType;
use App\Ebay\Library\ItemFilter\FeaturedOnly;
use App\Ebay\Library\ItemFilter\FeedbackScoreMax;
use App\Ebay\Library\ItemFilter\FeedbackScoreMin;
use App\Ebay\Library\ItemFilter\FreeShippingOnly;
use App\Ebay\Library\ItemFilter\GetItFastOnly;
use App\Ebay\Library\ItemFilter\GlobalId;
use App\Ebay\Library\ItemFilter\HideDuplicateItems;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\ItemFilter\ListedIn;
use App\Ebay\Library\ItemFilter\ListingType;
use App\Ebay\Library\ItemFilter\LocalPickupOnly;
use App\Ebay\Library\ItemFilter\LocalSearchOnly;
use App\Ebay\Library\ItemFilter\LocatedIn;
use App\Ebay\Library\ItemFilter\LotsOnly;
use App\Ebay\Library\ItemFilter\MaxBids;
use App\Ebay\Library\ItemFilter\MaxDistance;
use App\Ebay\Library\ItemFilter\MaxHandlingTime;
use App\Ebay\Library\ItemFilter\MaxPrice;
use App\Ebay\Library\ItemFilter\MaxQuantity;
use App\Ebay\Library\ItemFilter\MinBids;
use App\Ebay\Library\ItemFilter\MinPrice;
use App\Ebay\Library\ItemFilter\MinQuantity;
use App\Ebay\Library\ItemFilter\ModTimeFrom;
use App\Ebay\Library\ItemFilter\OutletSellerOnly;
use App\Ebay\Library\ItemFilter\PaginationInput;
use App\Ebay\Library\ItemFilter\PaymentMethod;
use App\Ebay\Library\ItemFilter\ReturnsAcceptedOnly;
use App\Ebay\Library\ItemFilter\Seller;
use App\Ebay\Library\ItemFilter\SellerBusinessType;
use App\Ebay\Library\ItemFilter\SoldItemsOnly;
use App\Ebay\Library\ItemFilter\SortOrder;
use App\Ebay\Library\ItemFilter\StartTimeFrom;
use App\Ebay\Library\ItemFilter\StartTimeTo;
use App\Ebay\Library\ItemFilter\TopRatedSellerOnly;
use App\Ebay\Library\ItemFilter\WorldOfGoodOnly;
use App\Library\Util\Util;
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

    public function test_currency()
    {
        $value = CurrencyInformation::AUSTRALIAN;

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::CURRENCY, [$value]);
        $dynamicErrors = $this->getDynamicErrors();

        $currency = new Currency(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($currency->validateDynamic());

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::CURRENCY, ['invalid']);

        $entersInvalidException = false;
        try {
            $currency = new Currency(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $currency->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_end_time_from()
    {
        $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));
        $currentDate->modify('+1 day');

        $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::END_TIME_FROM, $value);
        $dynamicErrors = $this->getDynamicErrors();

        $endTimeFrom = new EndTimeFrom(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($endTimeFrom->validateDynamic());

        $entersInvalidException = false;
        try {
            $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));

            $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::END_TIME_FROM, $value);

            $endTimeFrom = new EndTimeFrom(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $endTimeFrom->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_end_time_to()
    {
        $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));

        $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::END_TIME_TO, $value);
        $dynamicErrors = $this->getDynamicErrors();

        $endTimeFrom = new EndTimeTo(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($endTimeFrom->validateDynamic());

        $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));
        $currentDate->modify('+1 day');

        $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::END_TIME_TO, $value);
        $dynamicErrors = $this->getDynamicErrors();

        $endTimeFrom = new EndTimeTo(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($endTimeFrom->validateDynamic());

        $entersInvalidException = false;
        try {
            $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));
            $currentDate->modify('-1 day');

            $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::END_TIME_TO, $value);

            $endTimeFrom = new EndTimeTo(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $endTimeFrom->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_exclude_auto_pay()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXCLUDE_AUTO_PAY, $value);
            $excludeAutoPay = new ExcludeAutoPay(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($excludeAutoPay->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXCLUDE_AUTO_PAY, ['invalid']);

        $excludeAutoPay = new ExcludeAutoPay(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $excludeAutoPay->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_exclude_category()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [1, 2, 3, 4, 5],
            [5, 6, 6, 7, 8]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXCLUDE_CATEGORY, $value);
            $excludeCategory = new ExcludeCategory(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($excludeCategory->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXCLUDE_CATEGORY, ['invalid']);

        $excludeCategory = new ExcludeCategory(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $excludeCategory->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_exclude_seller()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            ['seller', 'seller', 'seller'],
            ['seller', 'seller', 'seller'],
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXCLUDE_SELLER, $value);
            $excludeSeller = new ExcludeSeller(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($excludeSeller->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXCLUDE_SELLER, [0]);

        $excludeSeller = new ExcludeSeller(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $excludeSeller->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_expedited_shipping_type()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = ['Expedited', 'OneDayShipping'];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXPEDITED_SHIPPING_TYPE, [$value]);
            $expeditedShippingType = new ExpeditedShippingType(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($expeditedShippingType->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::EXPEDITED_SHIPPING_TYPE, ['invalid']);

        $expeditedShippingType = new ExpeditedShippingType(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $expeditedShippingType->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_featured_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FEATURED_ONLY, $value);

            $featuredOnly = new FeaturedOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($featuredOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FEATURED_ONLY, ['invalid']);

        $featuredOnly = new FeaturedOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $featuredOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_feedback_score_max()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [1, 2, 3];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FEEDBACK_SCORE_MAX, [$value]);

            $feedbackScoreMax = new FeedbackScoreMax(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($feedbackScoreMax->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FEEDBACK_SCORE_MAX, ['invalid']);

        $feedbackScoreMax = new FeedbackScoreMax(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $feedbackScoreMax->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_feedback_score_min()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [1, 2, 3];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FEEDBACK_SCORE_MIN, [$value]);

            $feedbackScoreMin = new FeedbackScoreMin(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($feedbackScoreMin->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FEEDBACK_SCORE_MIN, ['invalid']);

        $feedbackScoreMin = new FeedbackScoreMin(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $feedbackScoreMin->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_free_shipping_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FREE_SHIPPING_ONLY, $value);

            $freeShippingOnly = new FreeShippingOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($freeShippingOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::FREE_SHIPPING_ONLY, ['invalid']);

        $freeShippingOnly = new FreeShippingOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $freeShippingOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_get_it_fast_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::GET_IT_FAST_ONLY, $value);

            $getItFastOnly = new GetItFastOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($getItFastOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::GET_IT_FAST_ONLY, ['invalid']);

        $getItFastOnly = new GetItFastOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $getItFastOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_hide_duplicate_items()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::HIDE_DUPLICATE_ITEMS, $value);

            $hideDuplicateItems = new HideDuplicateItems(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($hideDuplicateItems->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::HIDE_DUPLICATE_ITEMS, ['invalid']);

        $hideDuplicateItems = new HideDuplicateItems(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $hideDuplicateItems->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_listed_in()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $globalIds = GlobalIdInformation::instance()->getAll();

        foreach ($globalIds as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LISTED_IN, [$value['global-id']]);

            $listedIn = new ListedIn(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($listedIn->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LISTED_IN, ['invalid']);

        $listedIn = new ListedIn(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $listedIn->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_listing_type()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $listingTypes = ListingTypeInformation::instance()->getAll();

        foreach ($listingTypes as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LISTING_TYPE, [$value]);

            $listingType = new ListingType(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($listingType->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LISTING_TYPE, ['invalid']);

        $listingType = new ListingType(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $listingType->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_local_pickup_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOCAL_PICKUP_ONLY, $value);

            $localPickupOnly = new LocalPickupOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($localPickupOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOCAL_PICKUP_ONLY, ['invalid']);

        $localPickupOnly = new LocalPickupOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $localPickupOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_local_search_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOCAL_SEARCH_ONLY, $value);

            $localSearchOnly = new LocalSearchOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($localSearchOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOCAL_SEARCH_ONLY, ['invalid']);

        $localSearchOnly = new LocalSearchOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $localSearchOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_located_in()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = ISO3166CountryCodeInformation::instance()->getAll();

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOCATED_IN, [$value['alpha2']]);

            $locatedIn = new LocatedIn(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($locatedIn->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOCATED_IN, ['invalid']);

        $locatedIn = new LocatedIn(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $locatedIn->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_lots_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOTS_ONLY, $value);

            $lotsOnly = new LotsOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($lotsOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::LOTS_ONLY, ['invalid']);

        $lotsOnly = new LocalSearchOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $lotsOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_max_bids()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [0, 1, 24, 56];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_BIDS, [$value]);

            $maxBids = new MaxBids(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($maxBids->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_BIDS, ['invalid']);

        $maxBids = new MaxBids(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxBids->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_BIDS, [-1]);

        $maxBids = new MaxBids(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxBids->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_max_distance()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [5, 6, 24, 56];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_DISTANCE, [$value]);

            $maxDistance = new MaxDistance(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($maxDistance->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_DISTANCE, ['invalid']);

        $maxDistance = new MaxDistance(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxDistance->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_DISTANCE, [-1]);

        $maxDistance = new MaxDistance(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxDistance->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_DISTANCE, [4]);

        $maxDistance = new MaxDistance(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxDistance->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_max_handling_time()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [5, 6, 24, 56];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_HANDLING_TIME, [$value]);

            $maxHandlingTime = new MaxHandlingTime(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($maxHandlingTime->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_HANDLING_TIME, ['invalid']);

        $maxHandlingTime = new MaxHandlingTime(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxHandlingTime->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_HANDLING_TIME, [0]);

        $maxHandlingTime = new MaxHandlingTime(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxHandlingTime->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_DISTANCE, [-1]);

        $maxHandlingTime = new MaxHandlingTime(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxHandlingTime->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_max_price()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [0.1, 0.2, 1.02, 3.4];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::MAX_PRICE,
                [$value],
                'Currency',
                'USD'
            );

            $maxPrice = new MaxPrice(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($maxPrice->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MAX_PRICE,
            ['invalid'],
            'Currency',
            'USD'
        );

        $maxPrice = new MaxPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxPrice->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MAX_PRICE,
            [0],
            'Currency',
            'USD'
        );

        $maxPrice = new MaxPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxPrice->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MAX_PRICE,
            [-1],
            'Currency',
            'USD'
        );

        $maxPrice = new MaxPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxPrice->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_max_quantity()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [1, 5, 6, 24, 56];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_QUANTITY, [$value]);

            $maxQuantity = new MaxQuantity(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($maxQuantity->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_QUANTITY, ['invalid']);

        $maxQuantity = new MaxQuantity(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxQuantity->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_QUANTITY, [0]);

        $maxQuantity = new MaxQuantity(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxQuantity->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MAX_QUANTITY, [-1]);

        $maxQuantity = new MaxQuantity(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $maxQuantity->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_min_bids()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [0, 5, 6, 24, 56];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_BIDS, [$value]);

            $minBids = new MinBids(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($minBids->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_BIDS, ['invalid']);

        $minBids = new MinBids(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minBids->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_BIDS, [-1]);

        $minBids = new MinBids(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minBids->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_min_price()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [0.1, 0.2, 1.02, 3.4];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::MIN_PRICE,
                [$value],
                'Currency',
                'USD'
            );

            $minPrice = new MinPrice(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($minPrice->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MIN_PRICE,
            ['invalid'],
            'Currency',
            'USD'
        );

        $minPrice = new MinPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minPrice->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MIN_PRICE,
            [0],
            'Currency',
            'USD'
        );

        $minPrice = new MinPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minPrice->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::MIN_PRICE,
            [-1],
            'Currency',
            'USD'
        );

        $minPrice = new MinPrice(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minPrice->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_min_quantity()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [1, 5, 6, 24, 56];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_QUANTITY, [$value]);

            $minQuantity = new MinQuantity(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($minQuantity->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_QUANTITY, ['invalid']);

        $minQuantity = new MinQuantity(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minQuantity->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_QUANTITY, [0]);

        $minQuantity = new MinQuantity(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minQuantity->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MIN_QUANTITY, [-1]);

        $minQuantity = new MinQuantity(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $minQuantity->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_mod_time_from()
    {
        $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));
        $currentDate->modify('+1 day');

        $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MOD_TIME_FROM, $value);
        $dynamicErrors = $this->getDynamicErrors();

        $modTimeFrom = new ModTimeFrom(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($modTimeFrom->validateDynamic());

        $entersInvalidException = false;
        try {
            $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));

            $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::MOD_TIME_FROM, $value);

            $modTimeFrom = new ModTimeFrom(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $modTimeFrom->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_outlet_seller_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::OUTLET_SELLER_ONLY, $value);

            $outletSellerOnly = new OutletSellerOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($outletSellerOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::OUTLET_SELLER_ONLY, ['invalid']);

        $outletSellerOnly = new OutletSellerOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $outletSellerOnly->validateDynamic();
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

    public function test_payment_method()
    {
        $value = [
            'PayPal',
            'PaisaPal',
            'PaisaPayEMI',
        ];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        foreach ($value as $item) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::PAYMENT_METHOD,
                [$item]
            );

            $paymentMethod = new PaymentMethod(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($paymentMethod->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::PAYMENT_METHOD,
            ['invalid']
        );

        $paymentMethod = new PaymentMethod(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $paymentMethod->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_returns_accepted_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::RETURNS_ACCEPTED_ONLY, $value);

            $returnsAcceptedOnly = new ReturnsAcceptedOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($returnsAcceptedOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::RETURNS_ACCEPTED_ONLY, ['invalid']);

        $returnsAcceptedOnly = new ReturnsAcceptedOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $returnsAcceptedOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_seller()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            ['seller1', 'seller2', 'seller3'],
            ['seller', 'seller', 'seller']
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::SELLER, [$value]);

            $seller = new Seller(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($seller->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::SELLER, [0]);

        $seller = new Seller(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $seller->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_seller_business_type()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $validSites = array(
            'EBAY-AT',
            'EBAY-NLBE',
            'EBAY-NLBE',
            'EBAY-FR',
            'EBAY-DE',
            'EBAY-IE',
            'EBAY-IT',
            'EBAY-PL',
            'EBAY-ES',
            'EBAY-CH',
            'EBAY-GB',
        );

        $validBusinessTypes = [
            'Business',
            'Private'
        ];

        $values = [];

        foreach ($validSites as $site) {
            $values[] = [
                'siteId' => $site,
                'businessType' => $validBusinessTypes[rand(0, 1)],
            ];
        }

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::SELLER_BUSINESS_TYPE, [$value]);

            $sellerBusinessType = new SellerBusinessType(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($sellerBusinessType->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::SELLER_BUSINESS_TYPE,
            [
                [
                    'siteId' => 'invalid'
                ]
            ]
        );

        $sellerBusinessType = new SellerBusinessType(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $sellerBusinessType->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::SELLER_BUSINESS_TYPE,
            [
                [
                    'businessType' => 'invalid'
                ]
            ]
        );

        $sellerBusinessType = new SellerBusinessType(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $sellerBusinessType->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::SELLER_BUSINESS_TYPE,
            [
                [
                    'businessType' => 'invalid',
                    'siteId' => 'EBAY-AT',
                ]
            ]
        );

        $sellerBusinessType = new SellerBusinessType(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $sellerBusinessType->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::SELLER_BUSINESS_TYPE,
            [
                [
                    'businessType' => 'Private',
                    'siteId' => 'EBAY-invalid',
                ]
            ]
        );

        $sellerBusinessType = new SellerBusinessType(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $sellerBusinessType->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_sold_items_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::SOLD_ITEMS_ONLY, $value);

            $soldItemsOnly = new SoldItemsOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($soldItemsOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::SOLD_ITEMS_ONLY, ['invalid']);

        $soldItemsOnly = new SoldItemsOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $soldItemsOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_sort_order()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $sortOrder = SortOrderInformation::instance()->getAll();

        foreach ($sortOrder as $item) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::SORT_ORDER,
                [$item]
            );

            $sortOrder = new SortOrder(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($sortOrder->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(
            ItemFilter::SORT_ORDER,
            ['invalid']
        );

        $sortOrder = new SortOrder(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $sortOrder->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_start_time_from()
    {
        $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));
        $currentDate->modify('+1 day');

        $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::START_TIME_FROM, $value);
        $dynamicErrors = $this->getDynamicErrors();

        $startTimeFrom = new StartTimeFrom(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($startTimeFrom->validateDynamic());

        $entersInvalidException = false;
        try {
            $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));

            $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::START_TIME_FROM, $value);

            $startTimeFrom = new StartTimeFrom(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $startTimeFrom->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_start_time_to()
    {
        $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));
        $currentDate->modify('+1 day');

        $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::START_TIME_TO, $value);
        $dynamicErrors = $this->getDynamicErrors();

        $startTimeTo = new StartTimeTo(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        static::assertTrue($startTimeTo->validateDynamic());

        $entersInvalidException = false;
        try {
            $currentDate = new \DateTime(Util::formatFromDate(new \DateTime()));

            $value = [$currentDate->format(Util::getDateTimeApplicationFormat())];

            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::START_TIME_TO, $value);

            $startTimeTo = new StartTimeTo(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            $startTimeTo->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_top_rated_seller_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::TOP_RATED_SELLER_ONLY, $value);

            $topRatedSellerOnly = new TopRatedSellerOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($topRatedSellerOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::TOP_RATED_SELLER_ONLY, ['invalid']);

        $topRatedSellerOnly = new TopRatedSellerOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $topRatedSellerOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_world_of_goods_only()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $values = [
            [true],
            [false]
        ];

        foreach ($values as $value) {
            $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::WORLD_OF_GOOD_ONLY, $value);

            $worldOfGoodOnly = new WorldOfGoodOnly(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($worldOfGoodOnly->validateDynamic());
        }

        $dynamicMetadata = $this->getDynamicMetadata(ItemFilter::WORLD_OF_GOOD_ONLY, ['invalid']);

        $worldOfGoodOnly = new WorldOfGoodOnly(
            $dynamicMetadata,
            $dynamicConfiguration,
            $dynamicErrors
        );

        $entersInvalidException = false;
        try {
            $worldOfGoodOnly->validateDynamic();
        } catch (\RuntimeException $e) {
            $entersInvalidException = true;
        }

        static::assertTrue($entersInvalidException);
    }

    public function test_global_id()
    {
        $dynamicConfiguration = $this->getDynamicConfiguration(false, false);
        $dynamicErrors = $this->getDynamicErrors();

        $globalIds = GlobalIdInformation::instance()->getAll();

        foreach ($globalIds as $globalId) {
            $dynamicMetadata = $this->getDynamicMetadata(
                ItemFilter::GLOBAL_ID,
                [$globalId['global-id']]
            );

            $globalId = new GlobalId(
                $dynamicMetadata,
                $dynamicConfiguration,
                $dynamicErrors
            );

            static::assertTrue($globalId->validateDynamic());
        }


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