<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Middleware\ResolvedMiddleware;
use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Ebay\Business\Request\StaticRequestConstructor;
use App\Ebay\Library\Response\ShoppingApi\GetShippingCostsResponse;
use App\Library\Middleware\SimpleMiddlewareBuilder;

class SingleItemEntryPoint
{
    /**
     * @var ResolvedMiddleware $resolvedMiddleware
     */
    private $resolvedMiddleware;
    /**
     * SingleItemEntryPoint constructor.
     * @param ResolvedMiddleware $resolvedMiddleware
     */
    public function __construct(
        ResolvedMiddleware $resolvedMiddleware
    ) {
        $this->resolvedMiddleware = $resolvedMiddleware;
    }
    /**
     * @param SingleItemRequestModel $model
     * @return iterable
     */
    public function getSingleItem(SingleItemRequestModel $model): iterable
    {
        $parameters = ['model' => $model];

        return SimpleMiddlewareBuilder::instance($parameters)
            ->add($this->resolvedMiddleware->getAlreadyCachedMiddleware())
            ->add($this->resolvedMiddleware->getFetchSingleItemMiddleware())
            ->run();
    }

    public function getShippingCostsForItem(ItemShippingCostsRequestModel $singleItemRequestModel)
    {
        $shoppingApiModel = StaticRequestConstructor::createEbayShippingCostsItemRequest(
            $singleItemRequestModel->getItemId(),
            $singleItemRequestModel->getDestinationCountryCode()
        );

        /** @var GetShippingCostsResponse $getShippingCostsResponse */
        $getShippingCostsResponse = $this->shoppingApiEntryPoint->getShippingCosts($shoppingApiModel);


    }
}