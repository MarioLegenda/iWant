<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;
use App\Doctrine\Entity\EbayBusinessEntity;
use App\Doctrine\Repository\EbayBusinessEntityRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsAdvanced;
use App\Ebay\Presentation\FindingApi\Model\FindItemsByKeywords;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Ebay\Library\ItemFilter\ItemFilter as ItemFilterConstants;
use App\Library\Util\TypedRecursion;

class EbayModelFactory
{
    /**
     * @var int $batchModels
     */
    private $batchModels;
    /**
     * @var EbayBusinessEntityRepository $ebayBusinessEntityRepository
     */
    private $ebayBusinessEntityRepository;
    /**
     * EbayModelFactory constructor.
     * @param EbayBusinessEntityRepository $ebayBusinessEntityRepository
     * @param int $batchModels
     */
    public function __construct(
        EbayBusinessEntityRepository $ebayBusinessEntityRepository,
        int $batchModels
    ) {
        $this->ebayBusinessEntityRepository = $ebayBusinessEntityRepository;
        $this->batchModels = $batchModels;
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @return array|iterable
     */
    public function createFindItemsAdvancedModel(
        SearchModelInterface $model
    ): array {
        $this->validateModel($model);

        $findingApiModels = [];

        for ($i = 0; $i < $this->batchModels; $i++) {
            $itemFilters = TypedArray::create('integer', ItemFilter::class);
            $queries = TypedArray::create('integer', Query::class);

            $this->createRequiredQueries($model, $queries);
            $this->createPagination($model, $queries, ($i + 1));
            $this->createRequiredItemFilters($model, $itemFilters);
            $this->createModelSpecificItemFilters($model, $itemFilters);
            $this->createOutputSelector([
                'StoreInfo',
                'SellerInfo',
                'GalleryInfo',
                'PictureURLLarge',
                'PictureURLSuperSize',
            ], $itemFilters);
            $this->createSortOrder($model, $itemFilters);

            $findItemsInEbayStores = new FindItemsAdvanced($queries);

            $findingApiModels[] = new FindingApiModel($findItemsInEbayStores, $itemFilters);
        }

        return $findingApiModels;
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @param TypedArray $itemFilters
     */
    public function createModelSpecificItemFilters(
        SearchModelInterface $model,
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

        if ($model->isHideDuplicateItems()) {
            $hideDuplicatedItems = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::HIDE_DUPLICATE_ITEMS,
                [$model->isHideDuplicateItems()]
            ));

            $itemFilters[] = $hideDuplicatedItems;
        }
    }
    /**
     * @param SearchModel|SearchModelInterface|InternalSearchModel $model
     * @param TypedArray $queries
     */
    public function createRequiredQueries(
        SearchModelInterface $model,
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
            $model->getInternalPagination()->getLimit()
        );
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @param TypedArray $queries
     * @param int $i
     */
    public function createPagination(
        SearchModelInterface $model,
        TypedArray $queries,
        int $i
    ): void {
        $queries[] = new Query(
            'paginationInput.pageNumber',
            $i
        );
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @param TypedArray $itemFilters
     */
    public function createRequiredItemFilters(
        SearchModelInterface $model,
        TypedArray $itemFilters
    ) {
        if ($model->isSearchStores()) {
            $businessEntites = $this->ebayBusinessEntityRepository->findBusinessesByGlobalId($model->getGlobalId());

            $businessNames = map_reduce($businessEntites, function(EbayBusinessEntity $businessEntity) {
                return $businessEntity->getDisplayName();
            });

            $sellers = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SELLER,
                [$businessNames]
            ));

            $itemFilters[] = $sellers;
        }

        if (!empty($model->getShippingCountries())) {
            $shippingCountries = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::AVAILABLE_TO,
                [$model->getShippingCountries()]
            ));

            $itemFilters[] = $shippingCountries;
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
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     * @param TypedArray $itemFilters
     */
    private function createSortOrder(
        SearchModelInterface $model,
        TypedArray $itemFilters
    ) {
        if ($model->isBestMatch()) {
            $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SORT_ORDER,
                ['BestMatch']
            ));
        }

        if ($model->isNewlyListed()) {
            $itemFilters[] = new ItemFilter(new ItemFilterMetadata(
                'name',
                'value',
                ItemFilterConstants::SORT_ORDER,
                ['StartTimeNewest']
            ));
        }
    }
    /**
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     */
    private function validateModel(SearchModelInterface $model)
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