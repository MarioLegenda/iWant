<?php

namespace App\App\Business;

use App\App\Library\Response\MarketplaceFactoryResponse;
use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemOptionsResponse;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Cache\Implementation\SingleProductItemCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Model\Translations;
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
     * @var ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     */
    private $itemTranslationCacheImplementation;
    /**
     * Finder constructor.
     * @param CountryRepository $countryRepository
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     * @param ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     * @param Router $router
     */
    public function __construct(
        CountryRepository $countryRepository,
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        SingleProductItemCacheImplementation $singleProductItemCacheImplementation,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation,
        Router $router
    ) {
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->countryRepository = $countryRepository;
        $this->singleProductItemCacheImplementation = $singleProductItemCacheImplementation;
        $this->router = $router;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
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

        $singleItemArray = json_decode($singleProductItem->getResponse(), true)['singleItem'];

        $translations = $this->getTranslations($model->getItemId());

        $singleItemArray = $this->putTranslationsIntoSingleItemArray(
            $translations,
            $model->getLocale(),
            $singleItemArray
        );

        $singleItemArray = $this->filterSingleItemValues($singleItemArray);

        return new SingleItemResponseModel(
            $model->getItemId(),
            $singleItemArray
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
                        'locale' => $model->getLocale(),
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

        $singleItemArray = $responseModel->getSingleItem()->toArray();

        $translations = $this->getTranslations($model->getItemId());

        $singleItemArray = $this->putTranslationsIntoSingleItemArray(
            $translations,
            $model->getLocale(),
            $singleItemArray
        );

        $singleItemResponseModel = new SingleItemResponseModel(
            $model->getItemId(),
            $singleItemArray
        );

        $this->singleProductItemCacheImplementation->store(
            $model->getItemId(),
            json_encode($singleItemResponseModel->toArray()),
            MarketplaceType::fromValue('Ebay')
        );

        return $singleItemResponseModel;
    }
    /**
     * @param string $itemId
     * @return Translations
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getTranslations(string $itemId): Translations
    {
        $itemTranslation = $this->itemTranslationCacheImplementation->getStoredByItemId($itemId);

        return new Translations(json_decode($itemTranslation->getTranslations(), true));
    }
    /**
     * @param Translations $translations
     * @param string $locale
     * @param array $singleItem
     * @return array
     */
    private function putTranslationsIntoSingleItemArray(
        Translations $translations,
        string $locale,
        array $singleItem
    ): array {
        $items = ['title'];

        foreach ($items as $item) {
            if ($translations->hasEntryByLocale($item, $locale)) {
                $singleItem[$item] = $translations->getEntryByLocale($item, $locale)->getTranslation();
            }
        }

        return $singleItem;
    }
    /**
     * @param array $singleItemArray
     * @return array
     */
    private function filterSingleItemValues(array $singleItemArray)
    {
        $toFilter = [
            'itemId',
            'autoPay',
            'title',
            'endTime',
            'endTimeApplicationFormat',
            'priceInfo',
            'quantity',
            'seller'
        ];

        return array_filter($singleItemArray, function($item, $key) use ($toFilter) {
            return in_array($key, $toFilter);
        }, ARRAY_FILTER_USE_BOTH);
    }
}