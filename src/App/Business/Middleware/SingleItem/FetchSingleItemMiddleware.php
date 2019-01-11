<?php

namespace App\App\Business\Middleware\SingleItem;

use App\App\Business\Middleware\MiddlewareResult;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\App\Presentation\Model\Response\SingleItemResponseModel;
use App\Cache\Implementation\SingleProductItemCacheImplementation;
use App\Ebay\Business\Request\StaticRequestConstructor;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetSingleItemResponse;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\MarketplaceType;
use App\Library\Middleware\MiddlewareEntryInterface;
use App\Library\Middleware\MiddlewareResultInterface;
use App\Translation\TranslationCenter;

class FetchSingleItemMiddleware implements MiddlewareEntryInterface
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var TranslationCenter $translationCenter
     */
    private $translationCenter;
    /**
     * @var SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    private $singleProductItemCacheImplementation;
    /**
     * FetchSingleItemMiddleware constructor.
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param TranslationCenter $translationCenter
     * @param SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    public function __construct(
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        TranslationCenter $translationCenter,
        SingleProductItemCacheImplementation $singleProductItemCacheImplementation
    ) {
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->translationCenter = $translationCenter;
        $this->singleProductItemCacheImplementation = $singleProductItemCacheImplementation;
    }
    /**
     * @param MiddlewareResultInterface|null $middlewareResult
     * @param array|null $parameters
     * @return MiddlewareResultInterface
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(
        MiddlewareResultInterface $middlewareResult = null,
        array $parameters = null
    ): MiddlewareResultInterface {
        if ($middlewareResult->isFulfilled()) {
            return new MiddlewareResult($middlewareResult->getResult(), true);
        }

        /** @var SingleItemRequestModel $model */
        $model = $parameters['model'];

        /** @var ShoppingApiModel|ShoppingApiRequestModelInterface $requestModel */
        $requestModel = StaticRequestConstructor::createEbaySingleItemRequest($model->getItemId());

        /** @var GetSingleItemResponse $responseModel */
        $responseModel = $this->shoppingApiEntryPoint->getSingleItem($requestModel);

        if ($responseModel->getRoot()->isFailure()) {
            return new MiddlewareResult(null, false);
        }

        $singleItemArray = $responseModel->getSingleItem()->toArray();

        $singleItemArray = $this->translationCenter->translateArray(
            $singleItemArray,
            [
                'title',
                'description',
                'conditionDisplayName' => [
                    'pre_translate' => function(string $key, array $item) {
                        if ($key === 'conditionDisplayName') {
                            return $item['condition']['conditionDisplayName'];
                        }
                    },
                    'post_translate' => function(string $key, string $translated, array $item) {
                        if ($key === 'conditionDisplayName') {
                            $condition = $item['condition'];

                            $condition['conditionDisplayName'] = $translated;

                            return [
                                'key' => 'condition',
                                'value' => $condition
                            ];
                        }
                    }
                ],
                'conditionDescription' => [
                    'pre_translate' => function(string $key, array $item) {
                        if ($key === 'conditionDescription') {
                            return $item['condition']['conditionDescription'];
                        }
                    },
                    'post_translate' => function(string $key, string $translated, array $item) {
                        if ($key === 'conditionDescription') {
                            $condition = $item['condition'];

                            $condition['conditionDescription'] = $translated;

                            return [
                                'key' => 'condition',
                                'value' => $condition
                            ];
                        }
                    }
                ]
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

        return new MiddlewareResult($singleItemArray, true);
    }
}