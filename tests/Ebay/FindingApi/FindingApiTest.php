<?php

namespace App\Tests\Ebay\FindingApi;

use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\Json\PaginationOutput;
use App\Ebay\Library\Response\FindingApi\Json\Result\Condition;
use App\Ebay\Library\Response\FindingApi\Json\Result\ListingInfo;
use App\Ebay\Library\Response\FindingApi\Json\Result\SellingStatus;
use App\Ebay\Library\Response\FindingApi\Json\Result\ShippingInfo;
use App\Ebay\Library\Response\FindingApi\Json\Root;
use App\Ebay\Library\Response\FindingApi\Json\SearchResult;
use App\Ebay\Library\Response\FindingApi\JsonFindingApiResponseModel;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Tests\Library\BasicSetup;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider;

class FindingApiTest extends BasicSetup
{
    public function test_finding_api_find_items_by_keywords()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $model = $dataProvider->getFindItemsByKeywordsData('boots for mountain');

        /** @var JsonFindingApiResponseModel $responseModel */
        $responseModel = $findingApiEntryPoint->findItemsByKeywords($model);

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $responseModel);

        $rootItem = $responseModel->getRoot();

        static::assertInstanceOf(Root::class, $rootItem);

        $this->assertRootItem($rootItem);
        $this->assertPaginationOutput($responseModel->getPaginationOutput());
        $this->assertSearchResults($responseModel->getSearchResults());
    }

    public function test_finding_api_find_items_advanced()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $model = $dataProvider->getFindItemsAdvanced(
            'Lady gaga'
        );

        /** @var JsonFindingApiResponseModel $responseModel */
        $responseModel = $findingApiEntryPoint->findItemsAdvanced($model);

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $responseModel);

        $rootItem = $responseModel->getRoot();

        static::assertInstanceOf(Root::class, $rootItem);

        $this->assertRootItem($rootItem);
        $this->assertPaginationOutput($responseModel->getPaginationOutput());
        $this->assertSearchResults($responseModel->getSearchResults());
    }

    public function test_find_items_in_ebay_stores()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $model = $dataProvider->getFindItemsInEbayStores(
            urlencode('lady gaga')
        );

        /** @var JsonFindingApiResponseModel $responseModel */
        $responseModel = $findingApiEntryPoint->findItemsInEbayStores($model);

        $rootItem = $responseModel->getRoot();

        static::assertInstanceOf(Root::class, $rootItem);

        $this->assertRootItem($rootItem);
        $this->assertPaginationOutput($responseModel->getPaginationOutput());
        $this->assertSearchResults($responseModel->getSearchResults());
    }

    public function test_get_version()
    {
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');

        $versionModel = $findingApiEntryPoint->getVersion($dataProvider->getGetVersionModel());

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $versionModel);
        static::assertEquals('Success', $versionModel->getRoot()->getAck());
    }
    /**
     * @param Root $rootItem
     */
    private function assertRootItem(Root $rootItem)
    {
        static::assertInternalType('string', $rootItem->getVersion());
        static::assertInternalType('string', $rootItem->getTimestamp());
        static::assertInternalType('string', $rootItem->getAck());
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
     * @param array|null $searchResultsContainer
     */
    private function assertSearchResults(array $searchResultsContainer = null)
    {
        static::assertGreaterThan(0, count($searchResultsContainer));

        /** @var SearchResult $item */
        foreach ($searchResultsContainer as $item) {
            static::assertInstanceOf(SearchResult::class, $item);

            static::assertInternalType('string', $item->getItemId());
            static::assertInternalType('string', $item->getTitle());
            static::assertInternalType('string', $item->getGlobalId());
            if (!empty($item->getGalleryUrl())) {
                static::assertInternalType('string', $item->getGalleryUrl());
            }

            static::assertInstanceOf(ShippingInfo::class, $item->getShippingInfo());
            static::assertInstanceOf(SellingStatus::class, $item->getSellingStatus());
            static::assertInstanceOf(ListingInfo::class, $item->getListingInfo());

            static::assertInstanceOf(\DateTime::class, $item->getListingInfo()->getEndTimeAsObject());
            static::assertInstanceOf(\DateTime::class, $item->getListingInfo()->getStartTimeAsObject());
            static::assertInternalType('string', $item->getViewItemUrl());


            if (!is_null($item->getCondition())) {
                static::assertInstanceOf(Condition::class, $item->getCondition());
            }
        }
    }
}