<?php

namespace App\Web\EntryPoint;

use App\Component\TodayProducts\TodayProductsComponent;

class TodayProductsEntryPoint
{
    /**
     * @var TodayProductsComponent $todayProductsComponent
     */
    private $todayProductsComponent;
    /**
     * TodayProductsEntryPoint constructor.
     * @param TodayProductsComponent $todayProductsComponent
     */
    public function __construct(
        TodayProductsComponent $todayProductsComponent
    ) {
        $this->todayProductsComponent = $todayProductsComponent;
    }

    public function getTodaysProducts()
    {

    }
}