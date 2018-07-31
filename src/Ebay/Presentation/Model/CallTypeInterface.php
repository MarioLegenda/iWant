<?php

namespace App\Ebay\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;

interface CallTypeInterface
{
    /**
     * @return string
     */
    public function getOperationName(): string;
    /**
     * @return string
     */
    public function getQueryName(): string;
    /**
     * @return TypedArray
     */
    public function getQueryValues(): TypedArray;
}