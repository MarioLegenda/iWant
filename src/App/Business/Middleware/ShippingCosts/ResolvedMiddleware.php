<?php

namespace App\App\Business\Middleware\ShippingCosts;

class ResolvedMiddleware
{
    /**
     * @var AlreadyCachedMiddleware $alreadyCachedMiddleware
     */
    private $alreadyCachedMiddleware;
    /**
     * @var FetchShippingCostsMiddleware $fetchShippingCostsMiddleware
     */
    private $fetchShippingCostsMiddleware;
    /**
     * ResolvedMiddleware constructor.
     * @param AlreadyCachedMiddleware $alreadyCachedMiddleware
     * @param FetchShippingCostsMiddleware $fetchShippingCostsMiddleware
     */
    public function __construct(
        AlreadyCachedMiddleware $alreadyCachedMiddleware,
        FetchShippingCostsMiddleware $fetchShippingCostsMiddleware
    ) {
        $this->alreadyCachedMiddleware = $alreadyCachedMiddleware;
        $this->fetchShippingCostsMiddleware = $fetchShippingCostsMiddleware;
    }
    /**
     * @return AlreadyCachedMiddleware
     */
    public function getAlreadyCachedMiddleware(): AlreadyCachedMiddleware
    {
        return $this->alreadyCachedMiddleware;
    }
    /**
     * @return FetchShippingCostsMiddleware
     */
    public function getFetchShippingCostsMiddleware(): FetchShippingCostsMiddleware
    {
        return $this->fetchShippingCostsMiddleware;
    }
}