<?php

namespace App\Tests\Component;

use App\Component\Search\Model\Request\SearchModel;
use App\Component\Search\SearchComponent;
use App\Tests\Component\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class SearchComponentTest extends BasicSetup
{
    public function test_ebay_search()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        /** @var SearchModel $model */
        $model = $dataProvider->createSearchRequestModel();

        //$ebayProducts = $searchComponent->searchEbay($model);
    }
}