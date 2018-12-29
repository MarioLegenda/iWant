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
     * @var ModifyResultMiddleware $modifyResultMiddleware
     */
    private $modifyResultMiddleware;
    /**
     * ResolvedMiddleware constructor.
     * @param AlreadyCachedMiddleware $alreadyCachedMiddleware
     * @param FetchSingleItemMiddleware $fetchSingleItemMiddleware
     * @param ModifyResultMiddleware $modifyResultMiddleware
     */
    public function __construct(
        AlreadyCachedMiddleware $alreadyCachedMiddleware,
        FetchSingleItemMiddleware $fetchSingleItemMiddleware,
        ModifyResultMiddleware $modifyResultMiddleware
    ) {
        $this->alreadyCachedMiddleware = $alreadyCachedMiddleware;
        $this->fetchSingleItemMiddleware = $fetchSingleItemMiddleware;
        $this->modifyResultMiddleware = $modifyResultMiddleware;
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
    /**
     * @return ModifyResultMiddleware
     */
    public function getModifyResultMiddleware(): ModifyResultMiddleware
    {
        return $this->modifyResultMiddleware;
    }
}