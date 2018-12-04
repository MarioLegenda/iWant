<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Cache\Implementation\SingleProductItemCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Model\Translations;
use App\Doctrine\Entity\SingleProductItem;
use App\Ebay\Business\Request\StaticRequestConstructor;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\MarketplaceType;
use App\Translation\YandexCacheableTranslationCenter;

class SingleItemEntryPoint
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     */
    private $itemTranslationCacheImplementation;
    /**
     * @var YandexCacheableTranslationCenter $translationCenter
     */
    private $translationCenter;
    /**
     * @var SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    private $singleProductItemCacheImplementation;
    /**
     * SingleItemEntryPoint constructor.
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param YandexCacheableTranslationCenter $translationCenter
     * @param SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     * @param ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     */
    public function __construct(
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        YandexCacheableTranslationCenter $translationCenter,
        SingleProductItemCacheImplementation $singleProductItemCacheImplementation,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation
    ) {
        $this->singleProductItemCacheImplementation = $singleProductItemCacheImplementation;
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->translationCenter = $translationCenter;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
    }
    /**
     * @param SingleItemRequestModel $model
     * @return iterable
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getSingleItem(SingleItemRequestModel $model): iterable
    {
        if ($this->singleProductItemCacheImplementation->isStored($model->getItemId())) {
            /** @var SingleProductItem $singleProductItem */
            $singleProductItem = $this->singleProductItemCacheImplementation->getStored($model->getItemId());

            $singleItemArray = json_decode($singleProductItem->getResponse(), true)['singleItem'];

            $singleItemArray = $this->translationCenter->translateArray(
                $singleItemArray,
                [
                    'title',
                    'description',
                    'conditionDisplayName',
                ],
                $model->getLocale(),
                $model->getItemId()
            );

            return $singleItemArray;
        }

        /** @var ShoppingApiModel|ShoppingApiRequestModelInterface $requestModel */
        $requestModel = StaticRequestConstructor::createEbaySingleItemRequest($model->getItemId());

        /** @var GetSingleItemResponse $responseModel */
        $responseModel = $this->shoppingApiEntryPoint->getSingleItem($requestModel);

        if ($responseModel->isErrorResponse()) {
            return null;
        }

        $singleItemArray = $responseModel->getSingleItem()->toArray();

        $singleItemArray = $this->translationCenter->translateArray(
            $singleItemArray,
            [
                'title',
                'description',
                'conditionDisplayName',
            ],
            $model->getLocale(),
            $model->getItemId()
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

        return $singleItemArray;
    }
}