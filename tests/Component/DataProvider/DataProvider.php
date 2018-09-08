<?php

namespace App\Tests\Component\DataProvider;

use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Entity\TodayKeyword;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Doctrine\Repository\TodaysKeywordRepository;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Tests\Library\FakerTrait;

class DataProvider
{
    use FakerTrait;
    /**
     * @param MarketplaceType|TypeInterface $marketplaceType
     * @param TodaysKeywordRepository $todaysKeywordRepository
     * @param NativeTaxonomy $nativeTaxonomy
     * @param int $numOfKeywords
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createKeywordsForMarketplace(
        MarketplaceType $marketplaceType,
        TodaysKeywordRepository $todaysKeywordRepository,
        NativeTaxonomy $nativeTaxonomy,
        int $numOfKeywords = 12
    ): void {
        for ($i = 0; $i < $numOfKeywords; $i++) {
            $todaysKeyword = $this->createKeyword(
                $this->faker()->name,
                $marketplaceType,
                $nativeTaxonomy
            );

            $todaysKeywordRepository->getManager()->persist($todaysKeyword);
        }

        $todaysKeywordRepository->getManager()->flush($todaysKeyword);
    }
    /**
     * @param ApplicationShopRepository $applicationShopRepository
     * @param NativeTaxonomy $nativeTaxonomy
     * @param MarketplaceType $marketplaceType
     * @param int $numOfShops
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createApplicationShops(
        ApplicationShopRepository $applicationShopRepository,
        NativeTaxonomy $nativeTaxonomy,
        MarketplaceType $marketplaceType,
        int $numOfShops = 10
    ): void {
        for ($i = 0; $i < $numOfShops; $i++) {
            $applicationShop = $this->createApplicationShop(
                $this->faker()->name,
                $marketplaceType,
                $nativeTaxonomy
            );

            $applicationShopRepository->persistAndFlush($applicationShop);
        }
    }
    /**
     * @param string $keyword
     * @param MarketplaceType $marketplace
     * @param NativeTaxonomy $taxonomy
     * @return TodayKeyword
     */
    private function createKeyword(
        string $keyword,
        MarketplaceType $marketplace,
        NativeTaxonomy $taxonomy
    ): TodayKeyword {
        return new TodayKeyword(
            $keyword,
            $marketplace,
            $taxonomy
        );
    }
    /**
     * @param string $name
     * @param MarketplaceType $marketplaceType
     * @param NativeTaxonomy $taxonomy
     * @return ApplicationShop
     */
    private function createApplicationShop(
        string $name,
        MarketplaceType $marketplaceType,
        NativeTaxonomy $taxonomy
    ): ApplicationShop
    {
        return new ApplicationShop(
            $name,
            $name,
            $marketplaceType,
            $taxonomy
        );
    }
}