<?php

namespace App\Tests\Component\DataProvider;

use App\Component\Search\Ebay\Model\Request\Pagination as EbayPagination;
use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\Range;
use App\Component\Search\Etsy\Model\Request\Pagination as EtsyPagination;
use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Etsy\Model\Request\SearchModel as EtsySearchModel;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Library\MarketplaceType;
use App\Tests\Library\FakerTrait;
use App\Web\Library\View\EbaySearchViewType;

class DataProvider
{
    use FakerTrait;
    /**
     * @param array $data
     * @return EbaySearchModel
     */
    public function createEbaySearchRequestModel(array $data = []): EbaySearchModel
    {
        $keyword = (isset($data['keyword'])) ? $data['keyword']: 'harry potter book';
        $lowestPrice = (isset($data['lowestPrice'])) ? $data['lowestPrice']: true;
        $bestMatch = (isset($data['bestMatch'])) ? $data['bestMatch'] : true;
        $highestPrice = (isset($data['highestPrice'])) ? $data['highestPrice']: false;
        $highQuality = (isset($data['highQuality'])) ? $data['highQuality']: false;
        $shippingCountries = (isset($data['shippingCountries'])) ? $data['shippingCountries']: [];
        $marketplaces = (isset($data['marketplaces'])) ? $data['marketplaces']: [];
        $taxonomies = (isset($data['taxonomies'])) ? $data['taxonomies']: [];
        $globalIds = $data['globalId'];
        $pagination = (isset($data['pagination']) and $data['pagination'] instanceof EbayPagination)
            ? $data['pagination']
            : new EbayPagination(4, 1);

        $internalPagination = (isset($data['internalPagination']) and $data['internalPagination'] instanceof EbayPagination)
            ? $data['internalPagination']
            : new EbayPagination(80, 1);

        $locale = (isset($data['locale']) ? $data['locale'] : 'en');

        $hideDuplicateItems = (isset($data['hideDuplicateItems'])) ? $data['hideDuplicateItems'] : false;

        return new EbaySearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $bestMatch,
            $shippingCountries,
            $marketplaces,
            $taxonomies,
            $pagination,
            $globalIds,
            $locale,
            $internalPagination,
            $hideDuplicateItems
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