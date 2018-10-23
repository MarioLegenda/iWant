<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Business\Factory\Metadata\MetadataCollection;
use App\Component\Search\Ebay\Business\Factory\Metadata\RootMetadata;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchRequestModel;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\EbayRootCategory;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Information\SellerBusinessTypeValidSitesInformation;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsInEbayStores;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;

class EbayModelFactory
{
    /**
     * @var ModelFactoryMetadataCollector $modelFactoryMetadataCollector
     */
    private $modelFactoryMetadataCollector;
    /**
     * EbayModelFactory constructor.
     * @param ModelFactoryMetadataCollector $modelFactoryMetadataCollector
     */
    public function __construct(
        ModelFactoryMetadataCollector $modelFactoryMetadataCollector
    ) {
        $this->modelFactoryMetadataCollector = $modelFactoryMetadataCollector;
    }
    /**
     * @param SearchModel $model
     * @return iterable|TypedArray
     */
    public function createRequestModels(SearchModel $model): iterable
    {
        $this->validateModel($model);

        /** @var MetadataCollection $metadataCollection */
        $metadataCollection = $this->modelFactoryMetadataCollector->createData($model);

        $requestModels = TypedArray::create('integer', SearchRequestModel::class);

        /** @var TypedArray|RootMetadata $metadata */
        $metadata = $metadataCollection->getMetadata();
        /** @var RootMetadata $item */
        foreach ($metadata as $item) {
            $requestModel = $this->createRequestModel($model, $item);

            $requestModels[] = new SearchRequestModel($item, $requestModel);
        }

        return $requestModels;
    }
    /**
     * @param SearchModel $model
     * @param RootMetadata $rootMetadata
     * @return FindingApiModel
     */
    private function createRequestModel(
        SearchModel $model,
        RootMetadata $rootMetadata
    ): FindingApiModel {
        $itemFilters = TypedArray::create('integer', ItemFilter::class);
        $queries = TypedArray::create('integer', Query::class);

        $this->createRequiredQueries($model, $rootMetadata, $queries);
        $this->createRequiredItemFilters($rootMetadata, $itemFilters);
        $this->createModelSpecificItemFilters($model, $itemFilters);
        $this->createCategoryIdItemFilter($rootMetadata, $itemFilters);
        $this->createOutputSelector([
            'SellerInfo',
            'StoreInfo',
            'GalleryInfo',
            'PictureURLLarge',
            'PictureURLSuperSize',
        ], $itemFilters);
        $this->createSortOrder($model, $itemFilters);

        $findItemsInEbayStores = new FindItemsInEbayStores($queries);

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
     * @param RootMetadata $rootMetadata
     * @param TypedArray $queries
     */
    public function createRequiredQueries(
        SearchModel $model,
        RootMetadata $rootMetadata,
        TypedArray $queries
    ) {
        $queries[] = new Query(
            'keywords',
            urlencode($model->getKeyword())
        );

        $queries[] = new Query(
            'GLOBAL-ID',
            $rootMetadata->getGlobalId()
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
     * @param RootMetadata $rootMetadata
     * @param TypedArray $itemFilters
     */
    public function createRequiredItemFilters(
        RootMetadata $rootMetadata,
        TypedArray $itemFilters
    ) {
        /** @var TypedArray $shops */
        $shops = $rootMetadata->getShops();

        $shopNames = $shops->filter(function(ApplicationShop $applicationShop) {
            return $applicationShop->getName();
        });

        if (SellerBusinessTypeValidSitesInformation::instance()->has($rootMetadata->getGlobalId())) {
            $sellerBussinessType = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SELLER_BUSINESS_TYPE,
                [[
                    'siteId' => $rootMetadata->getGlobalId(),
                    'businessType' => 'Business',
                ]]
            ));

            $itemFilters[] = $sellerBussinessType;
        }

        $sellers = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::SELLER,
            [array_values($shopNames)]
        ));

        $hideDuplicatedItems = new ItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            ItemFilterConstants::HIDE_DUPLICATE_ITEMS,
            [true]
        ));

        $itemFilters[] = $hideDuplicatedItems;
        $itemFilters[] = $sellers;
    }
    /**
     * @param RootMetadata $rootMetadata
     * @param TypedArray $itemFilters
     */
    public function createCategoryIdItemFilter(
        RootMetadata $rootMetadata,
        TypedArray $itemFilters
    ) {
        if ($rootMetadata->getGlobalId() === GlobalIdInformation::EBAY_MOTOR) {
            return;
        }

        if (!empty($rootMetadata->getTaxonomyMetadata())) {
            $taxonomyMetadata = $rootMetadata->getTaxonomyMetadata();

            $ebayRootCategoryIds = $taxonomyMetadata->getEbayRootCategories()->filter(function(EbayRootCategory $ebayRootCategory) {
                return (int) $ebayRootCategory->getCategoryId();
            });

            $categoryId = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::CATEGORY_ID,
                [$ebayRootCategoryIds]
            ));

            $itemFilters[] = $categoryId;
        }
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
    }
}