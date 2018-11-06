<?php

namespace App\App\Business;

use App\App\Library\Response\MarketplaceFactoryResponse;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\Cache\Implementation\SingleProductItemCacheImplementation;
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
     * @var SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    private $singleProductItemCacheImplementation;
    /**
     * Finder constructor.
     * @param MarketplaceFactoryFinder $marketplaceFactoryFinder
     * @param MarketplaceFactoryResponse $marketplaceFactoryResponse
     * @param CountryRepository $countryRepository
     * @param SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    public function __construct(
        MarketplaceFactoryFinder $marketplaceFactoryFinder,
        MarketplaceFactoryResponse $marketplaceFactoryResponse,
        CountryRepository $countryRepository,
        SingleProductItemCacheImplementation $singleProductItemCacheImplementation
    ) {
        $this->marketplaceFactoryFinder = $marketplaceFactoryFinder;
        $this->marketplaceFactoryResponse = $marketplaceFactoryResponse;
        $this->countryRepository = $countryRepository;
        $this->singleProductItemCacheImplementation = $singleProductItemCacheImplementation;
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
    /**
     * @param SingleItemOptionsModel $model
     * @return SingleItemOptionsResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOptionsForSingleItem(SingleItemOptionsModel $model): SingleItemOptionsResponse
    {
        if ($this->singleProductItemCacheImplementation->isStored($model->getItemId())) {
            return new SingleItemOptionsResponse(
                'GET',
                    'route',
                $model->getItemId()
            );
        }

        return new SingleItemOptionsResponse(
            'PUT',
                'route',
            $model->getItemId()
        );
    }
}