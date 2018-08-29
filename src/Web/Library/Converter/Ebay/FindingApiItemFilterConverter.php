<?php

namespace App\Web\Library\Converter\Ebay;

use App\Ebay\Library\Dynamic\BaseDynamic;
use App\Ebay\Library\Dynamic\DynamicInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Library\Util\Util;
use App\Web\Model\Request\RequestItemFilter;
use App\Web\Model\Request\UniformedRequestModel;

class FindingApiItemFilterConverter implements ItemFilterObservable
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
     * @var DynamicInterface[]|iterable $createdItemFilters
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
        $this->observers[] = $observer;

        return $this;
    }
    /**
     * @param ItemFilterObserver $observer
     * @return bool|void
     */
    public function detach(ItemFilterObserver $observer)
    {
        $observerGen = Util::createGenerator($this->observers);

        foreach ($observerGen as $item) {
            $attachedObserver = $item['item'];
            $key = $item['key'];

            if ($attachedObserver == $observer) {
                unset($this->observers[$key]);
                sort($this->observers);

                return;
            }
        }
    }
    /**
     * @return TypedArray
     */
    public function notify(): TypedArray
    {
        if (!$this->model instanceof UniformedRequestModel) {
            $message = sprintf(
                '%s is not initialized properly. You have to initialize it with the call %s::initializeWithModel()',
                get_class($this),
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        /** @var ItemFilterObserver $observer */
        foreach ($this->observers as $observer) {
            $this->createdItemFilters = array_merge($this->createdItemFilters, $observer->update(
                $this,
                $this->webItemFilters->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION))
            );
        }

        $createdItemFilters = TypedArray::create('integer', DynamicInterface::class);
        $createdItemFiltersGen = Util::createGenerator($this->createdItemFilters);

        foreach ($createdItemFiltersGen as $item) {
            /** @var BaseDynamic|DynamicInterface $itemFilter */
            $itemFilter = $item['item'];

            $createdItemFilters[] = $itemFilter;
        }

        return $createdItemFilters;
    }
}