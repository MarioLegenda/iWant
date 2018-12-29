<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Middleware\SingleItem\ResolvedMiddleware as SingleItemResolvedMiddleware;
use App\App\Business\Middleware\ShippingCosts\ResolvedMiddleware as ShippingCostsResolvedMiddleware;
use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\Middleware\SimpleMiddlewareBuilder;

class SingleItemEntryPoint
{
    /**
     * @var SingleItemResolvedMiddleware $singleItemResolvedMiddleware
     */
    private $singleItemResolvedMiddleware;
    /**
     * @var ShippingCostsResolvedMiddleware $shippingCostsResolvedMiddleware
     */
    private $shippingCostsResolvedMiddleware;
    /**
     * SingleItemEntryPoint constructor.
     * @param SingleItemResolvedMiddleware $singleItemResolvedMiddleware
     * @param ShippingCostsResolvedMiddleware $shippingCostsResolvedMiddleware
     */
    public function __construct(
        SingleItemResolvedMiddleware $singleItemResolvedMiddleware,
        ShippingCostsResolvedMiddleware $shippingCostsResolvedMiddleware
    ) {
        $this->singleItemResolvedMiddleware = $singleItemResolvedMiddleware;
        $this->shippingCostsResolvedMiddleware = $shippingCostsResolvedMiddleware;
    }
    /**
     * @param SingleItemRequestModel $model
     * @return iterable
     */
    public function getSingleItem(SingleItemRequestModel $model): iterable
    {
        $parameters = ['model' => $model];

        return SimpleMiddlewareBuilder::instance($parameters)
            ->add($this->singleItemResolvedMiddleware->getAlreadyCachedMiddleware())
            ->add($this->singleItemResolvedMiddleware->getFetchSingleItemMiddleware())
            ->run();
    }
    /**
     * @param ItemShippingCostsRequestModel $itemShippingCostsRequestModel
     * @return array
     */
    public function getShippingCostsForItem(ItemShippingCostsRequestModel $itemShippingCostsRequestModel): iterable
    {
        $parameters = ['model' => $itemShippingCostsRequestModel];

        return SimpleMiddlewareBuilder::instance($parameters)
            ->add($this->shippingCostsResolvedMiddleware->getAlreadyCachedMiddleware())
            ->add($this->shippingCostsResolvedMiddleware->getFetchShippingCostsMiddleware())
            ->run();
    }
}