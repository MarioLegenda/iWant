<?php

namespace App\Component\TodayProducts\Selector\Ebay;


use App\Doctrine\Entity\ApplicationShop;

interface SubjectSelectorInterface
{
    /**
     * @param ApplicationShop $applicationShop
     * @return mixed
     */
    public function notify(ApplicationShop $applicationShop);
    /**
     * @param ObserverSelectorInterface $observer
     * @return mixed
     */
    public function attach(ObserverSelectorInterface $observer);
    /**
     * @param ObserverSelectorInterface $observer
     * @return mixed
     */
    public function detach(ObserverSelectorInterface $observer);
}