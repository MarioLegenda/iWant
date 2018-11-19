<?php

namespace App\Tests\Component;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\SearchComponent;
use App\Tests\Component\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class SearchComponentTest extends BasicSetup
{
    public function test_ebay_prepared_search()
    {
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
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'internalPagination' => new Pagination($internalLimit, $internalPage),
            'pagination' => new Pagination($limit, $page),
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
}