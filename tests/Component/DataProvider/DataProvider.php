<?php

namespace App\Tests\Component\DataProvider;

use App\Component\Search\Ebay\Model\Request\Pagination as EbayPagination;
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
        $highestPrice = (isset($data['highestPrice'])) ? $data['highestPrice']: false;
        $highQuality = (isset($data['highQuality'])) ? $data['highQuality']: false;
        $shippingCountries = (isset($data['shippingCountries'])) ? $data['shippingCountries']: [];
        $marketplaces = (isset($data['marketplaces'])) ? $data['marketplaces']: [];
        $taxonomies = (isset($data['taxonomies'])) ? $data['taxonomies']: [];
        $globalIds = (isset($data['globalIds'])) ? $data['globalIds'] : [];
        $pagination = (isset($data['pagination']) and $data['pagination'] instanceof Pagination)
            ? $data['pagination']
            : new EbayPagination(4, 2);

        $viewType = (isset($data['viewType'])) ? $data['viewType'] : EbaySearchViewType::fromValue('globalIdView');

        return new EbaySearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $shippingCountries,
            $marketplaces,
            $taxonomies,
            $pagination,
            $viewType,
            $globalIds
        );
    }
    /**
     * @param array $data
     * @return EtsySearchModel
     */
    public function createEtsySearchRequestModel(array $data = [])
    {
        $keyword = (isset($data['keyword'])) ? $data['keyword']: 'harry potter book';
        $lowestPrice = (isset($data['lowestPrice'])) ? $data['lowestPrice']: true;
        $highestPrice = (isset($data['highestPrice'])) ? $data['highestPrice']: false;
        $highQuality = (isset($data['highQuality'])) ? $data['highQuality']: false;
        $shippingCountries = (isset($data['shippingCountries'])) ? $data['shippingCountries']: [];
        $pagination = (isset($data['pagination']) and $data['pagination'] instanceof Pagination)
            ? $data['pagination']
            : new EtsyPagination(4, 2);

        return new EtsySearchModel(
            $keyword,
            $lowestPrice,
            $highestPrice,
            $highQuality,
            $shippingCountries,
            $pagination
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