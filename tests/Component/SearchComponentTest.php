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
    public function test_ebay_prepared_search()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        /** @var EbaySearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel([
            'keyword' => 'iphone 7',
            'locale' => 'en',
            'lowestPrice' => false,
            'highQuality' => true,
            'highestPrice' => false,
            'globalId' => 'EBAY-FR',
            'pagination' => new Pagination(4, 1),
        ]);

        $responseArray = $searchComponent->getEbayProductsByGlobalId($model);

        static::assertNotEmpty($responseArray);
    }
}