<?php

namespace App\Web\Library\Converter;

interface ItemFilterObserver
{
    /**
     * @param ItemFilterObservable $observable
     * @param array $webItemFilters
     * @return array
     */
    public function update(ItemFilterObservable $observable, array $webItemFilters): array;
}