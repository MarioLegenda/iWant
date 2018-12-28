<?php

namespace App\App\Business\Middleware\SingleItem;

use App\App\Business\Middleware\MiddlewareResult;
use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Cache\Implementation\SingleProductItemCacheImplementation;
use App\Doctrine\Entity\SingleProductItem;
use App\Library\Middleware\MiddlewareEntryInterface;
use App\Library\Middleware\MiddlewareResultInterface;
use App\Translation\TranslationCenter;

class AlreadyCachedMiddleware implements MiddlewareEntryInterface
{
    /**
     * @var SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     */
    private $singleProductItemCacheImplementation;
    /**
     * @var TranslationCenter $translationCenter
     */
    private $translationCenter;
    /**
     * AlreadyCachedMiddleware constructor.
     * @param SingleProductItemCacheImplementation $singleProductItemCacheImplementation
     * @param TranslationCenter $translationCenter
     */
    public function __construct(
        SingleProductItemCacheImplementation $singleProductItemCacheImplementation,
        TranslationCenter $translationCenter
    ) {
        $this->singleProductItemCacheImplementation = $singleProductItemCacheImplementation;
        $this->translationCenter = $translationCenter;
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
        /** @var SingleItemRequestModel $model */
        $model = $parameters['model'];

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

            return new MiddlewareResult($singleItemArray, true);
        }

        return new MiddlewareResult(null, false);
    }
}