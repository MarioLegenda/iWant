<?php

namespace App\Component\Selector\Ebay;

use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\RootItem;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;

class ProductSelector implements \SplSubject
{
    /**
     * @var \SplObserver[] $observers
     */
    private $observers;
    /**
     * @var array $productResponseModels
     */
    private $productResponseModels;
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * ProductSelector constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     */
    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->productResponseModels = TypedArray::create('integer', ResponseModelInterface::class);
    }
    /**
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $message = sprintf(
            'Method %s::detach() is disabled',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param \SplObserver $observer
     * @return \SplSubject
     */
    public function attach(\SplObserver $observer): \SplSubject
    {
        $this->observers[] = $observer;

        return $this;
    }
    /**
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function notify(): void
    {
        /** @var \SplObserver|ObserverSelectorInterface $observer */
        foreach ($this->observers as $observer) {
            $model = $observer->update($this);

            /** @var FindingApiResponseModelInterface $responseModel */
            $responseModel = $this->findingApiEntryPoint->findItemsInEbayStores($model);

            /** @var RootItem $rootItem */
            $rootItem = $responseModel->getRoot();

            if ($rootItem->getSearchResultsCount() > 0) {
                $this->productResponseModels[] = $responseModel;

                $this->observers = [];

                break;
            }
        }
    }
    /**
     * @return iterable|TypedArray
     */
    public function getProductResponseModels(): iterable
    {
        return $this->productResponseModels;
    }
}