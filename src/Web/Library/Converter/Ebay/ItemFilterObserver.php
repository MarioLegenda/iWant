<?php

namespace App\Web\Library\Converter\Ebay;

interface ItemFilterObserver
{
    /**
     * @param ItemFilterObservable $observable
     * @param array $webItemFilters
     * @return array
     */
    public function update(ItemFilterObservable $observable, array $webItemFilters): array;
}