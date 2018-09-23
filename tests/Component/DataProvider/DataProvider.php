<?php

namespace App\Tests\Component\DataProvider;

use App\Component\Search\Request\Model\SearchRequestModel;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Library\MarketplaceType;
use App\Tests\Library\FakerTrait;

class DataProvider
{
    use FakerTrait;
    /**
     * @param string $keyword
     * @return SearchRequestModel
     */
    public function getSearchRequestModel(string $keyword)
    {
        return new SearchRequestModel($keyword, 12, 0);
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