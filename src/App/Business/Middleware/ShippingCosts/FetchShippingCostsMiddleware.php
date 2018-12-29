<?php

namespace App\App\Business\Middleware\ShippingCosts;

use App\App\Business\Cache\UniqueShippingCostsIdentifierFactory;
use App\App\Business\Middleware\MiddlewareResult;
use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\Cache\Implementation\ShippingCostsCacheImplementation;
use App\Ebay\Business\Request\StaticRequestConstructor;
use App\Ebay\Library\Response\ShoppingApi\GetShippingCostsResponse;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Library\Middleware\MiddlewareEntryInterface;
use App\Library\Middleware\MiddlewareResultInterface;

class FetchShippingCostsMiddleware implements MiddlewareEntryInterface
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var ShippingCostsCacheImplementation $shippingCostsCacheImplementation
     */
    private $shippingCostsCacheImplementation;
    /**
     * FetchShippingCostsMiddleware constructor.
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param ShippingCostsCacheImplementation $shippingCostsCacheImplementation
     */
    public function __construct(
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        ShippingCostsCacheImplementation $shippingCostsCacheImplementation
    ) {
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->shippingCostsCacheImplementation = $shippingCostsCacheImplementation;
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
            return $middlewareResult;
        }

        /** @var ItemShippingCostsRequestModel $model */
        $model = $parameters['model'];

        $shoppingApiModel = StaticRequestConstructor::createEbayShippingCostsItemRequest(
            $model->getItemId(),
            $model->getDestinationCountryCode()
        );

        /** @var GetShippingCostsResponse $getShippingCostsResponse */
        $getShippingCostsResponse = $this->shoppingApiEntryPoint->getShippingCosts($shoppingApiModel);

        if (!$getShippingCostsResponse->getRoot()->isValid()) {
            return null;
        }

        $resultArray = [
            'itemId' => $model->getItemId(),
            'shippingCostsSummary' => $getShippingCostsResponse->getShippingCostsSummary()->toArray(),
            'eligibleForPickupInStore' => $getShippingCostsResponse->isEligibleForPickupInStore(),
            'shippingDetails' => $getShippingCostsResponse->getShippingDetails()->toArray(),
        ];

        $this->shippingCostsCacheImplementation->store(
            UniqueShippingCostsIdentifierFactory::createIdentifier($model),
            $model->getItemId(),
            jsonEncodeWithFix($resultArray)
        );

        return new MiddlewareResult($resultArray, true);
    }
}