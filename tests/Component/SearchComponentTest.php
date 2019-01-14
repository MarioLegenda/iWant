<?php

namespace App\Tests\Component;

use App\Component\Search\Ebay\Business\SearchAbstraction;
use App\Component\Search\Ebay\Library\Exception\EbayEmptyResultException;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\Range;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\SearchComponent;
use App\Library\Util\Util;
use App\Tests\Component\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class SearchComponentTest extends BasicSetup
{
    public function test_internal_pagination_flow()
    {
        static::markTestSkipped();

        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $internalLimit = 80;
        $limit = 4;
        $maxProducts = 160;
        $page = 0;
        $internalPage = 1;

        $modelArray = [
            'keyword' => 'harry potter',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'internalPagination' => new Pagination($internalLimit, $internalPage),
            'pagination' => new Pagination($limit, $page),
            'hideDuplicateItems' => true,
            'doubleLocaleSearch' => false,
            'shippingCountries' => [],
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $totalProducts = [];
        $hasIncreasedInternalPagination = false;
        while(($internalPage * $internalLimit) <= $maxProducts) {
            $modelArray['pagination'] = new Pagination($limit, ++$page);
            $model = $dataProvider->createEbaySearchRequestModel($modelArray);

            $products = $searchComponent->getProductsPaginated($model);

            $totalProducts = array_merge($totalProducts, $products);

            if (($page * $limit) >= $internalLimit) {
                $hasIncreasedInternalPagination = true;
                $modelArray['internalPagination'] = new Pagination($internalLimit, ++$internalPage);
                $page = 0;
                $modelArray['pagination'] = new Pagination($limit, $page);

                $model = $dataProvider->createEbaySearchRequestModel($modelArray);

                $searchComponent->saveProducts($model);
            }
        }

        static::assertTrue($hasIncreasedInternalPagination);
        static::assertEquals(count($totalProducts), $maxProducts);
    }

    public function test_ships_to_filter()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'hideDuplicateItems' => false,
            'doubleLocaleSearch' => false,
            'fixedPrice' => false,
            'shippingCountries' => ['AF'],
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertNotEmpty($products);
    }

    public function test_empty_result()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'ssgdfhdsfg',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'hideDuplicateItems' => false,
            'doubleLocaleSearch' => false,
            'fixedPrice' => false,
            'shippingCountries' => [],
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $entersException = false;
        try {
            $searchComponent->saveProducts($model);
        } catch (EbayEmptyResultException $e) {
            $entersException = true;
        }

        static::assertTrue($entersException);
    }

    public function test_generic_testing()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'hideDuplicateItems' => false,
            'doubleLocaleSearch' => false,
            'fixedPrice' => false,
            'shippingCountries' => [],
            'watchCount' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertEquals(count($products), $model->getPagination()->getLimit());
    }

    public function test_lowest_price_filtered_result()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-FR',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 2),
            'hideDuplicateItems' => true,
            'doubleLocaleSearch' => false,
            'fixedPrice' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertEquals(count($products), $model->getPagination()->getLimit());

        $productsGen = Util::createGenerator($products);

        $previous = null;
        foreach ($productsGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];
            $current = (float) $item['price']['price'];

            if ($key === 0) {
                $previous = (float) $item['price']['price'];

                continue;
            }

            if ($previous > $current) {
                throw new \RuntimeException(sprintf(
                    'Failed asserting that %f is less or equal to %f',
                    $previous,
                    $current
                ));
            }
        }
    }

    public function test_watch_count_filter()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-FR',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 2),
            'hideDuplicateItems' => false,
            'doubleLocaleSearch' => false,
            'fixedPrice' => false,
            'watchCount' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertEquals(count($products), $model->getPagination()->getLimit());

        $productsGen = Util::createGenerator($products);

        $previous = null;
        foreach ($productsGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];
            $current = (float) $item['listingInfo']['watchCount'];

            if ($key === 0) {
                $previous = (float) $item['listingInfo']['watchCount'];

                continue;
            }

            if ($previous < $current) {
                throw new \RuntimeException(sprintf(
                    'Failed asserting that %f is less or equal to %f',
                    $previous,
                    $current
                ));
            }
        }
    }

    public function test_highest_price_filtered_result()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'harry potter',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => true,
            'globalId' => 'EBAY-DE',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'hideDuplicateItems' => false,
            'doubleLocaleSearch' => false,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertEquals(count($products), $model->getPagination()->getLimit());

        $productsGen = Util::createGenerator($products);

        $previous = null;
        foreach ($productsGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];
            $current = (float) $item['price']['price'];

            if ($key === 0) {
                $previous = (float) $item['price']['price'];

                continue;
            }

            if ($previous < $current) {
                throw new \RuntimeException(sprintf(
                    'Failed asserting that %f is higher or equal to %f',
                    $previous,
                    $current
                ));
            }
        }
    }

    public function test_double_locale_search()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'garden hose',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-ES',
            'fixedPrice' => true,
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'doubleLocaleSearch' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertNotEmpty($products);
        static::assertInternalType('array', $products);
    }

    public function test_fixed_price_search_only()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'maceta de jardín',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-ES',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'doubleLocaleSearch' => false,
            'fixedPrice' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        foreach ($products as $product) {
            static::assertTrue($product['isFixedPrice']);
        }

        static::assertNotEmpty($products);
        static::assertInternalType('array', $products);
    }

    public function test_store_search()
    {
        static::markTestSkipped();

        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-GB',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'doubleLocaleSearch' => false,
            'fixedPriceOnly' => true,
            'searchStores' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertNotEmpty($products);
        static::assertInternalType('array', $products);
    }

    public function test_sorting_methods()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7',
            'sortingMethod' => 'bestMatch',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-GB',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'doubleLocaleSearch' => false,
            'fixedPrice' => true,
            'searchStores' => false,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $searchComponent->saveProducts($model);

        $products = $searchComponent->getProductsPaginated($model);

        static::assertNotEmpty($products);
        static::assertInternalType('array', $products);
    }

    public function test_sqf()
    {
        /** @var SearchAbstraction $searchAbstraction */
        $searchAbstraction = $this->locator->get(SearchAbstraction::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        $modelArray = [
            'keyword' => 'iphone 7 mask',
            'sortingMethod' => 'bestMatch',
            'locale' => 'en',
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-FR',
            'internalPagination' => new Pagination(80, 1),
            'pagination' => new Pagination(8, 1),
            'doubleLocaleSearch' => false,
            'fixedPrice' => true,
            'searchStores' => false,
            'searchQueryFilter' => true,
            'hideDuplicateItems' => true,
        ];

        /** @var SearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel($modelArray);

        $products = $searchAbstraction->getProducts($model);

        $batchRequestsNum = $this->locator->getParameter('search_component_batch_requests_num');

        static::assertLessThan($batchRequestsNum * 80, count($products));
    }
}