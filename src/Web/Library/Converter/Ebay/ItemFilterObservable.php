<?php

namespace App\Web\Library\Converter\Ebay;

use App\Library\Infrastructure\Helper\TypedArray;

interface ItemFilterObservable
{
    public function attach(ItemFilterObserver $observer): ItemFilterObservable;
    public function notify(): TypedArray;
}