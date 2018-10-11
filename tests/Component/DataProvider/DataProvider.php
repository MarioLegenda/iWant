<?php

namespace App\Tests\Component\DataProvider;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Library\MarketplaceType;
use App\Tests\Library\FakerTrait;

class DataProvider
{
    use FakerTrait;
    /**
     * @param array $data
     * @return SearchModel
     */
    public function createSearchRequestModel(array $data = []): SearchModel
    {
        $keyword = (isset($data['keyword'])) ? $data['keyword']: 'harry potter book';
        $lowestPrice = (isset($data['lowestPrice'])) ? $data['lowestPrice']: true;
        $highestPrice = (isset($data['highestPrice'])) ? $data['highestPrice']: false;
        $highQuality = (isset($data['highQuality'])) ? $data['highQuality']: false;
        $shippingCountries = (isset($data['shippingCountries'])) ? $data['shippingCountries']: [];
        $marketplaces = (isset($data['marketplaces'])) ? $data['marketplaces']: [];
        $taxonomies = (isset($data['taxonomies'])) ? $data['taxonomies']: [];
        $globalIds = (isset($data['globalIds'])) ? $data['globalIds'] : [];
        $pagination = (isset($data['pagination']) and $data['pagination'] instanceof Pagination)
            ? $data['pagination']
            : new Pagination(4, 2);

        return new SearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $shippingCountries,
            $marketplaces,
            $taxonomies,
            $pagination,
            $globalIds
        );
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