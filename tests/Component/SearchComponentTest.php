<?php

namespace App\Tests\Component;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Ebay\Model\Response\PreparedEbayResponse;
use App\Component\Search\SearchComponent;
use App\Tests\Component\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class SearchComponentTest extends BasicSetup
{
    public function test_ebay_advanced_search()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        /** @var EbaySearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel([
            'keyword' => 'harry potter',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'pagination' => new Pagination(4, 1),
        ]);

        $preparedEbayResponse = $searchComponent->prepareEbayProductsAdvanced($model);
    }

    public function test_search_by_unique_name()
    {

        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        /** @var EbaySearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel([
            'keyword' => 'harry potter',
            'lowestPrice' => false,
            'highQuality' => false,
            'highestPrice' => false,
            'globalId' => 'EBAY-DE',
            'pagination' => new Pagination(4, 1),
        ]);

        /** @var PreparedEbayResponse $preparedEbayResponse */
        $preparedEbayResponse = $searchComponent->prepareEbayProductsAdvanced($model);
        
        $preparedItemsSearchModel = new PreparedItemsSearchModel(
            $preparedEbayResponse->getUniqueName(),
            new Pagination(8, 1)
        );
    }
}