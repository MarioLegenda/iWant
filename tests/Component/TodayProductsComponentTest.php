<?php

namespace App\Tests\Component;

use App\Component\Request\Model\TodayProduct;
use App\Component\TodayProducts\TodayProductsComponent;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Doctrine\Repository\TodaysKeywordRepository;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Library\Representation\MarketplaceRepresentation;
use App\Library\Representation\NativeTaxonomyRepresentation;
use App\Tests\Component\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;

class TodayProductsComponentTest extends BasicSetup
{
    public function setUp()
    {
        parent::setUp();

        /** @var TodaysKeywordRepository $todaysKeywordsRepository */
        $todaysKeywordsRepository = $this->locator->get(TodaysKeywordRepository::class);

        $em = $todaysKeywordsRepository->getManager();

        $em->getConnection()->exec('TRUNCATE todays_keywords');
        $em->getConnection()->exec('TRUNCATE application_shops');
    }

    public function test_todays_products_component()
    {
        $this->loadKeywordsAndApplicationShops();

        $component = $this->locator->get(TodayProductsComponent::class);

        $component->getTodaysProducts(new TodayProduct(new \DateTime()));
    }
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function loadKeywordsAndApplicationShops(): void
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component.todays_keywords');
        /** @var TodaysKeywordRepository $todaysKeywordsRepository */
        $todaysKeywordsRepository = $this->locator->get(TodaysKeywordRepository::class);
        /** @var NativeTaxonomyRepresentation $nativeTaxonomyRepresentation */
        $nativeTaxonomyRepresentation = $this->locator->get(NativeTaxonomyRepresentation::class);
        /** @var MarketplaceRepresentation $marketplaceRepresentation */
        $marketplaceRepresentation = $this->locator->get(MarketplaceRepresentation::class);
        /** @var ApplicationShopRepository $applicationShopRepository */
        $applicationShopRepository = $this->locator->get(ApplicationShopRepository::class);

        /** @var MarketplaceType|TypeInterface $marketplace */
        foreach ($marketplaceRepresentation as $marketplace) {
            foreach ($nativeTaxonomyRepresentation as $nativeTaxonomy) {
                $dataProvider->createKeywordsForMarketplace(
                    $marketplace,
                    $todaysKeywordsRepository,
                    $nativeTaxonomy
                );

                $dataProvider->createApplicationShops(
                    $applicationShopRepository,
                    $nativeTaxonomy,
                    $marketplace
                );
            }
        }
    }
}