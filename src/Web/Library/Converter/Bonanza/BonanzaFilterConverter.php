<?php

namespace App\Web\Library\Converter\Bonanza;

use App\Bonanza\Presentation\Model\ItemFilter;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Web\Library\Converter\ItemFilterObservable;
use App\Web\Library\Converter\ItemFilterObserver;
use App\Web\Model\Request\RequestItemFilter;
use App\Web\Model\Request\UniformedRequestModel;

class BonanzaFilterConverter implements ItemFilterObservable
{
    /**
     * @var UniformedRequestModel $model
     */
    private $model;
    /**
     * @var RequestItemFilter[]|iterable|TypedArray $webItemFilters
     */
    private $webItemFilters;
    /**
     * @var ItemFilter[]|iterable $createdItemFilters
     */
    private $createdItemFilters = [];
    /**
     * @var ItemFilterObserver[]|iterable $observers
     */
    private $observers = [];
    /**
     * @param UniformedRequestModel $model
     * @return ItemFilterObservable
     */
    public function initializeWithModel(UniformedRequestModel $model): ItemFilterObservable
    {
        $this->model = $model;
        $this->webItemFilters = $model->getItemFilters();

        return $this;
    }
    /**
     * @param ItemFilterObserver $observer
     * @return ItemFilterObservable
     */
    public function attach(ItemFilterObserver $observer): ItemFilterObservable
    {
        $this->checkInitialization();

        $this->observers[] = $observer;

        return $this;
    }
    /**
     * @return TypedArray
     */
    public function notify(): TypedArray
    {
        $this->checkInitialization();

        /** @var ItemFilterObserver $observer */
        foreach ($this->observers as $observer) {
            $this->createdItemFilters = array_merge($this->createdItemFilters, $observer->update(
                $this,
                $this->webItemFilters->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION))
            );
        }

        $createdItemFilters = TypedArray::create('integer', ItemFilter::class);
        $createdItemFiltersGen = Util::createGenerator($this->createdItemFilters);

        foreach ($createdItemFiltersGen as $item) {
            /** @var BaseDynamic|DynamicInterface $itemFilter */
            $itemFilter = $item['item'];

            $createdItemFilters[] = $itemFilter;
        }

        return $createdItemFilters;
    }
    /**
     * @throws \RuntimeException
     */
    private function checkInitialization()
    {
        if (!$this->model instanceof UniformedRequestModel) {
            $message = sprintf(
                '%s is not initialized properly. You have to initialize it with the call %s::initializeWithModel()',
                get_class($this),
                get_class($this)
            );

            throw new \RuntimeException($message);
        }
    }
}