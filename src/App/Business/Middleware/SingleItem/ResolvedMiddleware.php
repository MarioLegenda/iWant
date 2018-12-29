<?php

namespace App\App\Business\Middleware\SingleItem;

class ResolvedMiddleware
{
    /**
     * @var AlreadyCachedMiddleware $alreadyCachedMiddleware
     */
    private $alreadyCachedMiddleware;
    /**
     * @var FetchSingleItemMiddleware $fetchSingleItemMiddleware
     */
    private $fetchSingleItemMiddleware;
    /**
     * ResolvedMiddleware constructor.
     * @param AlreadyCachedMiddleware $alreadyCachedMiddleware
     * @param FetchSingleItemMiddleware $fetchSingleItemMiddleware
     */
    public function __construct(
        AlreadyCachedMiddleware $alreadyCachedMiddleware,
        FetchSingleItemMiddleware $fetchSingleItemMiddleware
    ) {
        $this->alreadyCachedMiddleware = $alreadyCachedMiddleware;
        $this->fetchSingleItemMiddleware = $fetchSingleItemMiddleware;
    }
    /**
     * @return AlreadyCachedMiddleware
     */
    public function getAlreadyCachedMiddleware(): AlreadyCachedMiddleware
    {
        return $this->alreadyCachedMiddleware;
    }
    /**
     * @return FetchSingleItemMiddleware
     */
    public function getFetchSingleItemMiddleware(): FetchSingleItemMiddleware
    {
        return $this->fetchSingleItemMiddleware;
    }
}