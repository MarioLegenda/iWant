<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Library\Infrastructure\Helper\TypedArray;

interface ResponseItemsInterface
{
    /**
     * @return TypedArray
     */
    public function getItems(): TypedArray;
}