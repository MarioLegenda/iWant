<?php

namespace App\Tests\Component;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Etsy\Model\Request\SearchModel as EtsySearchModel;
use App\Component\Search\SearchComponent;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Util\TypedRecursion;
use App\Tests\Component\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;
use App\Web\Library\Grouping\Grouping;

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

        /** @var EbaySearchModel $model */
        $model = $dataProvider->createEbaySearchRequestModel([
            'lowestPrice' => true,
            'highQuality' => false,
            'highestPrice' => false,
            'taxonomies' => [],
            'pagination' => new Pagination(4, 1)
        ]);

        $ebayProducts = $searchComponent->searchEbay($model);

        static::assertNotEmpty($ebayProducts);
    }

    public function test_ebay_search_by_single_category()
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
            7 => 'healthBeauty',
            8 => 'fashion',
        ];

        foreach ($internalTaxonomyNames as $taxonomyIndex => $internalTaxonomyName) {
            $internalTaxonomyName = $internalTaxonomyNames[$taxonomyIndex];

            /** @var NativeTaxonomy $nativeTaxonomy */
            $nativeTaxonomy = $nativeTaxonomyRepository->findOneBy([
                'internalName' => $internalTaxonomyName,
            ]);

            $chosenTaxonomyObjects[] = $nativeTaxonomy->toArray();

            /** @var EbaySearchModel $model */
            $model = $dataProvider->createEbaySearchRequestModel([
                'lowestPrice' => true,
                'highQuality' => false,
                'highestPrice' => false,
                'taxonomies' => [],
                'pagination' => new Pagination(4, 1)
            ]);

            $ebayProducts = $searchComponent->searchEbay($model);

            static::assertNotEmpty($ebayProducts);
        }
    }

    public function test_etsy_search()
    {
        /** @var SearchComponent $searchComponent */
        $searchComponent = $this->locator->get(SearchComponent::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component');

        /** @var EtsySearchModel $model */
        $model = $dataProvider->createEtsySearchRequestModel([
            'keyword' => 'hoover',
        ]);

        $etsyProducts = $searchComponent->searchEtsy($model);

        static::assertNotEmpty($etsyProducts);
    }
}