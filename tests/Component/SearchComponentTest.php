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
            'lowestPrice' => true,
            'highQuality' => true,
            'highestPrice' => false,
            'globalId' => 'EBAY-FR',
            'pagination' => new Pagination(80, 1),
        ]);

        $preparedEbayResponse = $searchComponent->prepareEbayProductsAdvanced($model);

        static::assertInstanceOf(PreparedEbayResponse::class, $preparedEbayResponse);

        $conn = $this->locator->get('doctrine')->getConnection();

        $conn->exec('TRUNCATE prepared_response_cache');
        $conn->exec('TRUNCATE search_cache');
        $conn->exec('TRUNCATE item_translation_cache');
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
            'pagination' => new Pagination(80, 1),
        ]);

        /** @var PreparedEbayResponse $preparedEbayResponse */
        $preparedEbayResponse = $searchComponent->prepareEbayProductsAdvanced($model);

        $limit = 8;

        $preparedItemsSearchModel = new PreparedItemsSearchModel(
            $preparedEbayResponse->getUniqueName(),
            'EBAY-DE',
            'en',
            false,
            new Pagination(8, 1)
        );

        $responseModels = $searchComponent->findEbaySearchByUniqueName($preparedItemsSearchModel);

        static::assertEquals(count($responseModels), $limit);

        $conn = $this->locator->get('doctrine')->getConnection();
    }
}