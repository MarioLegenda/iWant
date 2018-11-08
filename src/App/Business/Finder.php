<?php

namespace App\App\Business;

use App\App\Library\Response\MarketplaceFactoryResponse;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Cache\Implementation\SingleProductItemCacheImplementation;
use App\Doctrine\Entity\Country;
use App\Doctrine\Entity\SingleProductItem;
use App\Doctrine\Repository\CountryRepository;
use App\Ebay\Business\Request\StaticRequestConstructor;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\TypedRecursion;
use App\Symfony\Exception\HttpException;
use Symfony\Component\Routing\Router;

class Finder
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var CountryRepository $countryRepository
     */
    private $countryRepository;
    /**
     * @var SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    private $singleProductItemCacheImplementation;
    /**
     * @var Router $router
     */
    private $router;
    /**
     * Finder constructor.
     * @param CountryRepository $countryRepository
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     * @param Router $router
     */
    public function __construct(
        CountryRepository $countryRepository,
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        SingleProductItemCacheImplementation $singleProductItemCacheImplementation,
        Router $router
    ) {
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->countryRepository = $countryRepository;
        $this->singleProductItemCacheImplementation = $singleProductItemCacheImplementation;
        $this->router = $router;
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
     * @param SingleItemRequestModel $model
     * @return SingleItemResponseModel
     * @throws HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getSingleItem(SingleItemRequestModel $model)
    {
        if (!$this->singleProductItemCacheImplementation->isStored($model->getItemId())) {
            throw new HttpException(sprintf(
                'Non allowed usage of getting a single item'
            ));
        }

        /** @var SingleProductItem $singleProductItem */
        $singleProductItem = $this->singleProductItemCacheImplementation->getStored($model->getItemId());

        return new SingleItemResponseModel(
            $model->getItemId(),
            json_decode($singleProductItem->getResponse(), true)
        );
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
                    $this->router->generate('app_get_single_item', [
                        'itemId' => $model->getItemId(),
                    ]),
                $model->getItemId()
            );
        }

        return new SingleItemOptionsResponse(
            'PUT',
                $this->router->generate('app_put_single_item'),
            $model->getItemId()
        );
    }
    /**
     * @param SingleItemRequestModel $model
     * @return SingleItemResponseModel
     * @throws HttpException
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function putSingleItemInCache(SingleItemRequestModel $model): SingleItemResponseModel
    {
        if ($this->singleProductItemCacheImplementation->isStored($model->getItemId())) {
            throw new HttpException(sprintf(
                'Non allowed usage of getting a single item'
            ));
        }

        /** @var ShoppingApiModel|ShoppingApiRequestModelInterface $requestModel */
        $requestModel = StaticRequestConstructor::createEbaySingleItemRequest($model->getItemId());

        /** @var GetSingleItemResponse $responseModel */
        $responseModel = $this->shoppingApiEntryPoint->getSingleItem($requestModel);

        $singleItemResponseModel = new SingleItemResponseModel(
            $model->getItemId(),
            $responseModel->getSingleItem()->toArray()
        );

        $this->singleProductItemCacheImplementation->store(
            $model->getItemId(),
            json_encode($singleItemResponseModel->toArray()),
            MarketplaceType::fromValue('Ebay')
        );

        return $singleItemResponseModel;
    }
}