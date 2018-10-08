<?php

namespace App\Tests\Component;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\SearchComponent;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
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

        $nativeTaxonomyRepository = $this->locator->get(NativeTaxonomyRepository::class);

        $nativeTaxonomies = $nativeTaxonomyRepository->findAll();
        $nativeTaxonomyCount = count($nativeTaxonomies);

        $randKeys = array_rand($nativeTaxonomies, rand(1, $nativeTaxonomyCount));

        $chosenTaxonomies = [];

        /** @var NativeTaxonomy $nativeTaxonomy */
        foreach ($nativeTaxonomies as $nativeTaxonomy) {
            $chosenTaxonomies[] = $nativeTaxonomy->toArray();
        }

        /** @var SearchModel $model */
        $model = $dataProvider->createSearchRequestModel([
            'pagination' => new Pagination(8, 1)
        ]);

        $ebayProducts = $searchComponent->searchEbay($model);
    }
}