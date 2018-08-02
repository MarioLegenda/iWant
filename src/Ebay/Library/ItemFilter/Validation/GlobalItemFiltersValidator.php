<?php

namespace App\Ebay\Library\ItemFilter\Validation;

use App\Ebay\Library\Information\OutputSelectorInformation;
use App\Ebay\Library\Information\SortOrderInformation;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\ItemFilter\ItemFilterInterface;
use App\Library\Infrastructure\Helper\TypedArray;

class GlobalItemFiltersValidator
{
    /**
     * @param TypedArray $itemFilters
     */
    public function validate(TypedArray $itemFilters)
    {
        $foundFilters = $this->getDynamicsInBulk($itemFilters, [
            ItemFilter::EXCLUDE_SELLER,
            ItemFilter::SELLER,
            ItemFilter::TOP_RATED_SELLER_ONLY,
        ]);

        if (count($foundFilters) > 1) {
            $message = sprintf(
                'The ExcludeSeller item filter cannot be used together with either the Seller or TopRatedSellerOnly item filters or vice versa'
            );

            throw new \RuntimeException($message);
        }

        $foundFilters = $this->getDynamicsInBulk($itemFilters, [
            ItemFilter::AVAILABLE_TO,
            ItemFilter::LOCATED_IN,
        ]);

        if (count($foundFilters) > 1) {
            $message = sprintf(
                'AvailableTo item filter cannot be used together with LocatedIn item filter and vice versa'
            );

            throw new \RuntimeException($message);
        }

        if (isset($itemFilters[ItemFilter::LOCAL_SEARCH_ONLY])) {
            if (!isset($itemFilters[ItemFilter::MAX_DISTANCE]) or
                !isset($itemFilters[ItemFilter::BUYER_POSTAL_CODE])
            ) {
                $message = sprintf(
                    'LocalSearchOnly item filter has to be used together with MaxDistance item filter and buyerPostalCode'
                );

                throw new \RuntimeException($message);
            }
        }

        if (isset($itemFilters[ItemFilter::MAX_DISTANCE])) {
            if (!isset($itemFilters[ItemFilter::BUYER_POSTAL_CODE])) {
                $message = sprintf(
                    'MaxDistance item filter has to be used together with buyerPostalCode'
                );

                throw new \RuntimeException($message);
            }
        }

        if (isset($itemFilters[ItemFilter::FEEDBACK_SCORE_MIN]) and isset($itemFilters[ItemFilter::FEEDBACK_SCORE_MAX])) {
            $feedbackScoreMinValue = $itemFilters[ItemFilter::FEEDBACK_SCORE_MIN];
            $feedbackScoreMaxValue = $itemFilters[ItemFilter::FEEDBACK_SCORE_MAX];

            if ($feedbackScoreMaxValue < $feedbackScoreMinValue) {
                $message = sprintf(
                    'If provided, FeedbackScoreMax has to larger or equal than FeedbackScoreMin'
                );

                throw new \RuntimeException($message);
            }
        }

        if (isset($itemFilters[ItemFilter::MAX_BIDS]) and isset($itemFilters[ItemFilter::MIN_BIDS])) {
            $maxBidsValue = $itemFilters[ItemFilter::MAX_BIDS];
            $minBidsValue = $itemFilters[ItemFilter::MIN_BIDS];

            if ($maxBidsValue < $minBidsValue) {
                $message = sprintf(
                    'If provided, MaxBids has to larger or equal than MinBids'
                );

                throw new \RuntimeException($message);
            }
        }

        if (isset($itemFilters[ItemFilter::MAX_QUANTITY]) and isset($itemFilters[ItemFilter::MIN_QUANTITY])) {
            $maxQuantityValue = $itemFilters[ItemFilter::MAX_QUANTITY];
            $minQuantityValue = $itemFilters[ItemFilter::MIN_QUANTITY];

            if ($maxQuantityValue < $minQuantityValue) {
                $message = sprintf(
                    'If provided, MaxQuantity has to larger or equal than MinQuantity'
                );

                throw new \RuntimeException($message);
            }
        }

        if (isset($itemFilters[ItemFilter::OUTPUT_SELECTOR])) {
            $outputSelectorValues = $itemFilters[ItemFilter::OUTPUT_SELECTOR];

            foreach ($outputSelectorValues as $outputSelector) {
                if (!OutputSelectorInformation::instance()->has($outputSelector)) {
                    $message = sprintf(
                        'outputSelector \'%s\' is not supported by this version of FindingAPI. If ebay added it, add it manually in %s',
                        $outputSelector,
                        OutputSelectorInformation::class
                    );

                    throw new \RuntimeException($message);
                }
            }

            if (in_array('ConditionHistogram', $outputSelectorValues)) {

            }
        }

/*        if ($itemFilterStorage->hasDynamic('OutputSelector') and $itemFilterStorage->isDynamicInRequest('OutputSelector')) {
            $outputSelector = $itemFilterStorage->getDynamic('OutputSelector');

            foreach ($outputSelector['value'] as $selector) {
                if (!OutputSelectorInformation::instance()->has($selector)) {
                    throw new ItemFilterException('outputSelector \''.$selector.'\' is not supported by this version of FindingAPI. If ebay added it, add it manually in '.OutputSelectorInformation::class);
                }
            }

            if (in_array('ConditionHistogram', $outputSelector['value']) === true) {
                $globalId = strtolower($event->getRequest()->getGlobalParameters()->getParameter('global_id')->getValue());

                $validGlobalIds = array(
                    GlobalIdInformation::EBAY_MOTOR,
                    GlobalIdInformation::EBAY_IN,
                    GlobalIdInformation::EBAY_MY,
                    GlobalIdInformation::EBAY_PH,
                );

                if (in_array($globalId, $validGlobalIds) === true) {
                    throw new ItemFilterException('ConditionHistogram is supported for all eBay sites except US eBay Motors, India (IN), Malaysia (MY) and Philippines (PH)');
                }
            }
        }*/

        if (isset($itemFilters[ItemFilter::SORT_ORDER])) {
            $sortOrderValue = $itemFilters[ItemFilter::SORT_ORDER];

            if ($sortOrderValue === SortOrderInformation::BID_COUNT_FEWEST or $sortOrderValue === SortOrderInformation::BID_COUNT_MOST) {
                if (!isset($itemFilters[ItemFilter::LISTING_TYPE])) {
                    $message = sprintf(
                        'To sort by bid count, you must specify a listing type filter to limit results to auction listings only (such as, & itemFilter.name=ListingType&itemFilter.value=Auction)'
                    );

                    throw new \RuntimeException($message);
                }
            }
        }
    }
    /**
     * @param TypedArray $itemFilters
     * @param array $dynamics
     * @return mixed
     */
    private function getDynamicsInBulk(TypedArray $itemFilters, array $dynamics)
    {
        return $itemFilters->filter(function(iterable $data) use ($dynamics) {
            $items = [];
            /** @var ItemFilterInterface $item */
            foreach ($data as $item) {
                $dynamicMetadata = $item->getDynamicMetadata();
                $dynamicName = $dynamicMetadata->getName();

                if (in_array($dynamicName, $dynamics)) {
                    $items[] = $item;
                }
            }

            return $items;
        });
    }
}