<?php

namespace App\Component\Selector\Etsy;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;

class ProductSelector implements \SplSubject
{
    /**
     * @var TypedArray|iterable $productResponseModels
     */
    private $productResponseModels;
    /**
     * @var ObserverSelectorInterface[] $observers
     */
    private $observers;
    /**
     * @var EtsyApiEntryPoint $etsyApiEntryPoint
     */
    private $etsyApiEntryPoint;
    /**
     * ProductSelector constructor.
     * @param EtsyApiEntryPoint $etsyApiEntryPoint
     */
    public function __construct(
        EtsyApiEntryPoint $etsyApiEntryPoint
    ) {
        $this->etsyApiEntryPoint = $etsyApiEntryPoint;
        $this->productResponseModels = TypedArray::create('integer', EtsyApiResponseModelInterface::class);
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

    public function notify(): void
    {
        /** @var ObserverSelectorInterface $observer */
        foreach ($this->observers as $observer) {
            $model = $observer->update($this);

            switch ((string) $model->getMethodType()) {
                case 'FindAllShopListingsFeatured':
                    /** @var EtsyApiResponseModelInterface $responseModel */
                    $responseModel = $this->etsyApiEntryPoint->findAllShopListingsFeatured($model);

                    if ($responseModel->getCount() > 0) {
                        $this->productResponseModels[] = $responseModel;
                    }

                    $this->observers = [];

                    return;
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