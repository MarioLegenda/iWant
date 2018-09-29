<?php

namespace App\App\Business;

use App\App\Library\Response\MarketplaceFactoryResponse;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Doctrine\Entity\Country;
use App\Doctrine\Repository\CountryRepository;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;

class Finder
{
    /**
     * @var MarketplaceFactoryFinder $marketplaceFactoryFinder
     */
    private $marketplaceFactoryFinder;
    /**
     * @var MarketplaceFactoryResponse $marketplaceFactoryResponse
     */
    private $marketplaceFactoryResponse;
    /**
     * @var CountryRepository $countryRepository
     */
    private $countryRepository;
    /**
     * Finder constructor.
     * @param MarketplaceFactoryFinder $marketplaceFactoryFinder
     * @param MarketplaceFactoryResponse $marketplaceFactoryResponse
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        MarketplaceFactoryFinder $marketplaceFactoryFinder,
        MarketplaceFactoryResponse $marketplaceFactoryResponse,
        CountryRepository $countryRepository
    ) {
        $this->marketplaceFactoryFinder = $marketplaceFactoryFinder;
        $this->marketplaceFactoryResponse = $marketplaceFactoryResponse;
        $this->countryRepository = $countryRepository;
    }
    /**
     * @param SingleItemRequestModel $model
     * @return \App\Doctrine\Entity\SingleProductItem
     */
    public function getSingleItem(SingleItemRequestModel $model)
    {
        $singleItem = $this->marketplaceFactoryFinder
            ->getSingleItem($model->getMarketplace(), $model->getItemId());

        return $singleItem;
    }
    /**
     * @return TypedArray
     */
    public function getCountries(): TypedArray
    {
        $countries = $this->countryRepository->findAll();

        return TypedArray::create('integer', Country::class, $countries);
    }
}