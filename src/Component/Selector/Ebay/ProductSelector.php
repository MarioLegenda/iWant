<?php

namespace App\Component\Selector\Ebay;

use App\Component\TodayProducts\Model\TodayProduct;

class ProductSelector implements \SplSubject
{
    /**
     * @var \SplObserver[] $observers
     */
    private $observers;
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
     */
    public function attach(\SplObserver $observer)
    {
        $this->observers[] = $observer;
    }
    /**
     * @return TodayProduct|null
     */
    public function notify()
    {
        /** @var \SplObserver|ObserverSelectorInterface $observer */
        foreach ($this->observers as $observer) {
            $product = $observer->update($this);

            if ($product instanceof TodayProduct) {
                return $product;
            }
        }
    }
}