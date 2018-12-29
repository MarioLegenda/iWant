<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Middleware\SingleItem\ResolvedMiddleware as SingleItemResolvedMiddleware;
use App\App\Business\Middleware\ShippingCosts\ResolvedMiddleware as ShippingCostsResolvedMiddleware;
use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\Middleware\SimpleMiddlewareBuilder;
use App\Library\Result\ResultFactory;
use App\Library\Result\ResultInterface;

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
     * @return ResultInterface
     */
    public function getSingleItem(SingleItemRequestModel $model): ResultInterface
    {
        $parameters = ['model' => $model];

        $result = SimpleMiddlewareBuilder::instance($parameters)
            ->add($this->singleItemResolvedMiddleware->getAlreadyCachedMiddleware())
            ->add($this->singleItemResolvedMiddleware->getFetchSingleItemMiddleware())
            ->run();

        return ResultFactory::createResult($result);
    }
    /**
     * @param ItemShippingCostsRequestModel $itemShippingCostsRequestModel
     * @return ResultInterface
     */
    public function getShippingCostsForItem(ItemShippingCostsRequestModel $itemShippingCostsRequestModel): ResultInterface
    {
        $parameters = ['model' => $itemShippingCostsRequestModel];

        $result = SimpleMiddlewareBuilder::instance($parameters)
            ->add($this->shippingCostsResolvedMiddleware->getAlreadyCachedMiddleware())
            ->add($this->shippingCostsResolvedMiddleware->getFetchShippingCostsMiddleware())
            ->run();

        return ResultFactory::createResult($result);
    }
}