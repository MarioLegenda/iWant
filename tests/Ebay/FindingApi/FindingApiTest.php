<?php

namespace App\Tests\Ebay\FindingApi;

use App\Bonanza\Library\Response\ResponseItem\ListingInfo;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AspectHistogramContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\CategoryHistogramContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\ConditionHistogram\ConditionHistogram;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Category;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Condition;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\SellingStatus;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\ShippingInfo;
use App\Ebay\Library\Response\FindingApi\ResponseItem\ConditionHistogramContainer;
use App\Ebay\Library\Response\FindingApi\ResponseItem\PaginationOutput;
use App\Ebay\Library\Response\FindingApi\ResponseItem\RootItem;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class FindingApiTest extends BasicSetup
{
    public function test_finding_api_find_items_by_keywords()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $model = $dataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);

        $responseModel = $findingApiEntryPoint->findItemsByKeywords($model);

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $responseModel);

        $rootItem = $responseModel->getRoot();

        static::assertInstanceOf(RootItem::class, $rootItem);

        $this->assertRootItem($rootItem);
        $this->assertAspectHistogramContainer($responseModel->getAspectHistogramContainer());
        $this->assertConditionHistogramContainer($responseModel->getConditionHistogramContainer());
        $this->assertPaginationOutput($responseModel->getPaginationOutput());
        $this->assertCategoryHistogramContainer($responseModel->getCategoryHistogramContainer());
        $this->assertSearchResultsContainer($responseModel->getSearchResults());
    }
    /**
     * @param RootItem $rootItem
     */
    private function assertRootItem(RootItem $rootItem)
    {
        static::assertInternalType('string', $rootItem->getVersion());
        static::assertInternalType('string', $rootItem->getTimestamp());
        static::assertInternalType('string', $rootItem->getAck());
        static::assertInternalType('string', $rootItem->getNamespace());
        static::assertInternalType('int', $rootItem->getSearchResultsCount());
    }
    /**
     * @param AspectHistogramContainer|null $aspectHistogramContainer
     */
    private function assertAspectHistogramContainer(AspectHistogramContainer $aspectHistogramContainer = null)
    {
        if ($aspectHistogramContainer instanceof AspectHistogramContainer) {
            static::assertInternalType('string', $aspectHistogramContainer->getDomainDisplayName());
        } else {
            static::assertNull($aspectHistogramContainer);
        }
    }
    /**
     * @param ConditionHistogramContainer|null $conditionHistogramContainer
     */
    private function assertConditionHistogramContainer(ConditionHistogramContainer $conditionHistogramContainer = null)
    {
        if ($conditionHistogramContainer instanceof ConditionHistogramContainer) {
            if (!$conditionHistogramContainer->isEmpty()) {
                foreach ($conditionHistogramContainer as $item) {
                    static::assertInstanceOf(ConditionHistogram::class, $item);
                }
            }
        } else {
            static::assertNull($conditionHistogramContainer);
        }
    }
    /**
     * @param PaginationOutput|null $paginationOutput
     */
    private function assertPaginationOutput(PaginationOutput $paginationOutput)
    {
        static::assertInternalType('int', $paginationOutput->getTotalEntries());
        static::assertInternalType('int', $paginationOutput->getEntriesPerPage());
        static::assertInternalType('int', $paginationOutput->getPageNumber());
        static::assertInternalType('int', $paginationOutput->getTotalPages());
    }
    /**
     * @param CategoryHistogramContainer|null $categoryHistogramContainer
     */
    private function assertCategoryHistogramContainer(CategoryHistogramContainer $categoryHistogramContainer = null)
    {
        if ($categoryHistogramContainer instanceof CategoryHistogramContainer) {

        } else {
            static::assertNull($categoryHistogramContainer);
        }
    }
    /**
     * @param SearchResultsContainer|null $searchResultsContainer
     */
    private function assertSearchResultsContainer(SearchResultsContainer $searchResultsContainer = null)
    {
        static::assertInstanceOf(SearchResultsContainer::class, $searchResultsContainer);
        static::assertFalse($searchResultsContainer->isEmpty());
        static::assertGreaterThan(0, count($searchResultsContainer));

        /** @var Item $item */
        foreach ($searchResultsContainer as $item) {
            static::assertInstanceOf(Item::class, $item);

            static::assertInternalType('string', $item->getItemId());
            static::assertInternalType('string', $item->getTitle());
            static::assertInternalType('string', $item->getGlobalId());
            static::assertInternalType('string', $item->getGalleryUrl());
            static::assertInternalType('string', $item->getViewItemUrl());

            static::assertInstanceOf(Category::class, $item->getPrimaryCategory());

            $primaryCategory = $item->getPrimaryCategory();

            static::assertInternalType('string', $primaryCategory->getCategoryId());
            static::assertInternalType('string', $primaryCategory->getCategoryName());

            static::assertInstanceOf(ShippingInfo::class, $item->getShippingInfo());
            static::assertInstanceOf(SellingStatus::class, $item->getSellingStatus());
            static::assertInstanceOf(Condition::class, $item->getCondition());
        }
    }
}