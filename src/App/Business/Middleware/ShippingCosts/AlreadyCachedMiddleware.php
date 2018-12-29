<?php

namespace App\App\Business\Middleware\ShippingCosts;

use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use App\Cache\Implementation\ShippingCostsCacheImplementation;
use App\Doctrine\Entity\ShippingCostsItem;
use App\Library\Middleware\MiddlewareEntryInterface;
use App\Library\Middleware\MiddlewareResultInterface;
use App\App\Business\Middleware\MiddlewareResult;

class AlreadyCachedMiddleware implements MiddlewareEntryInterface
{
    /**
     * @var ShippingCostsCacheImplementation $shippingCostsCacheImplementation
     */
    private $shippingCostsCacheImplementation;
    /**
     * AlreadyCachedMiddleware constructor.
     * @param ShippingCostsCacheImplementation $shippingCostsCacheImplementation
     */
    public function __construct(
        ShippingCostsCacheImplementation $shippingCostsCacheImplementation
    ) {
        $this->shippingCostsCacheImplementation = $shippingCostsCacheImplementation;
    }
    /**
     * @param MiddlewareResultInterface|null $middlewareResult
     * @param array|null $parameters
     * @return MiddlewareResultInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(
        MiddlewareResultInterface $middlewareResult = null,
        array $parameters = null
    ): MiddlewareResultInterface {
        /** @var ItemShippingCostsRequestModel $model */
        $model = $parameters['model'];

        if ($this->shippingCostsCacheImplementation->isStored($model->getItemId())) {
            /** @var ShippingCostsItem $shippingCostsItem */
            $shippingCostsItem = $this->shippingCostsCacheImplementation->getStored($model->getItemId());

            $shippingCostsArray = json_decode($shippingCostsItem->getResponse(), true);

            return new MiddlewareResult($shippingCostsArray, true);
        }

        return new MiddlewareResult(null, false);
    }
}