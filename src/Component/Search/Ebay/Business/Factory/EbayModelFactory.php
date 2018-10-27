<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsAdvanced;
use App\Ebay\Presentation\FindingApi\Model\FindItemsInEbayStores;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class EbayModelFactory
{
    /**
     * @param SearchModel $model
     * @return FindingApiModel
     */
    public function createFindItemsAdvancedModel(
        SearchModel $model
    ): FindingApiModel {
        $this->validateModel($model);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);
        $queries = TypedArray::create('integer', Query::class);

        $this->createRequiredQueries($model, $queries);
        $this->createRequiredItemFilters($itemFilters);
        $this->createModelSpecificItemFilters($model, $itemFilters);
        $this->createOutputSelector([
            'SellerInfo',
            'StoreInfo',
            'GalleryInfo',
            'PictureURLLarge',
            'PictureURLSuperSize',
        ], $itemFilters);
        $this->createSortOrder($model, $itemFilters);

        $findItemsInEbayStores = new FindItemsAdvanced($queries);

        return new FindingApiModel($findItemsInEbayStores, $itemFilters);
    }
    /**
     * @param SearchModel $model
     * @param TypedArray $itemFilters
     */
    public function createModelSpecificItemFilters(
        SearchModel $model,
        TypedArray $itemFilters
    ) {

        if ($model->isHighQuality()) {
            $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::CONDITION,
                ['New', 2000, 2500]
            ));
        }

        if (!empty($model->getShippingCountries())) {
            $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::CONDITION,
                [apply_on_iterable($model->getShippingCountries(), function(array $val) {
                    return $val['alpha2Code'];
                })]
            ));
        }
    }
    /**
     * @param SearchModel $model
     * @param TypedArray $queries
     */
    public function createRequiredQueries(
        SearchModel $model,
        TypedArray $queries
    ) {
        $queries[] = new Query(
            'keywords',
            urlencode($model->getKeyword())
        );

        $queries[] = new Query(
            'GLOBAL-ID',
            $model->getGlobalId()
        );

        $queries[] = new Query(
            'paginationInput.entriesPerPage',
            $model->getPagination()->getLimit()
        );

        $queries[] = new Query(
            'paginationInput.pageNumber',
            $model->getPagination()->getPage()
        );
    }
    /**
     * @param TypedArray $itemFilters
     */
    public function createRequiredItemFilters(
        TypedArray $itemFilters
    ) {
        $hideDuplicatedItems = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::HIDE_DUPLICATE_ITEMS,
            [true]
        ));

        $itemFilters[] = $hideDuplicatedItems;
    }
    /**
     * @param array $selectors
     * @param TypedArray $itemFilters
     */
    private function createOutputSelector(array $selectors, TypedArray $itemFilters)
    {
        $outputSelector = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::OUTPUT_SELECTOR,
            [$selectors]
        ));

        $itemFilters[] = $outputSelector;
    }
    /**
     * @param SearchModel $model
     * @param TypedArray $itemFilters
     */
    private function createSortOrder(
        SearchModel $model,
        TypedArray $itemFilters
    ) {
        if ($model->isLowestPrice()) {
            $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SORT_ORDER,
                ['PricePlusShippingLowest']
            ));
        }

        if ($model->isHighestPrice()) {
            $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SORT_ORDER,
                ['CurrentPriceHighest']
            ));
        }
    }
    /**
     * @param SearchModel $model
     */
    private function validateModel(SearchModel $model)
    {
        if ($model->isHighestPrice() and $model->isLowestPrice()) {
            $message = sprintf(
                'Lowest price item filter cannot be used with the highest price item filter and vice versa'
            );

            throw new \RuntimeException($message);
        }

        if (!GlobalIdInformation::instance()->has($model->getGlobalId())) {
            $message = sprintf(
                'Global id %s does not exist',
                $model->getGlobalId()
            );

            throw new \RuntimeException($message);
        }
    }
}