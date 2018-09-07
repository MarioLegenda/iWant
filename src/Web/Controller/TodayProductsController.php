<?php

namespace App\Web\Controller;

use App\Web\EntryPoint\TodayProductsEntryPoint;

class TodayProductsController
{
    /**
     * @var TodayProductsEntryPoint $todayProductsEntryPoint
     */
    private $todayProductsEntryPoint;
    /**
     * TodayProductsController constructor.
     * @param TodayProductsEntryPoint $todayProductsEntryPoint
     */
    public function __construct(
        TodayProductsEntryPoint $todayProductsEntryPoint
    ) {
        $this->todayProductsEntryPoint = $todayProductsEntryPoint;
    }
}