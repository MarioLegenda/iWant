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

        $internalTaxonomyNames = [
            0 => 'booksMusicMovies',
            1 => 'autopartsMechanics',
            2 => 'homeGarden',
            3 => 'computersMobileGames',
            4 => 'sport',
            5 => 'antiquesArtCollectibles',
            6 => 'craftsHandmade',
            7 => 'fashion',
        ];

        $chosenTaxonomies = [5];

        $chosenTaxonomyObjects = [];
        foreach ($chosenTaxonomies as $chosenTaxonomy) {
            $internalTaxonomyName = $internalTaxonomyNames[$chosenTaxonomy];

            /** @var NativeTaxonomy $nativeTaxonomy */
            $nativeTaxonomy = $nativeTaxonomyRepository->findOneBy([
                'internalName' => $internalTaxonomyName,
            ]);

            $chosenTaxonomyObjects[] = $nativeTaxonomy->toArray();
        }

        /** @var SearchModel $model */
        $model = $dataProvider->createSearchRequestModel([
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'taxonomies' => [],
            'pagination' => new Pagination(4, 1)
        ]);

        $ebayProducts = $searchComponent->searchEbay($model);
    }
}