<?php

namespace App\Tests\Component\DataProvider;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Entity\TodayKeyword;
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
}