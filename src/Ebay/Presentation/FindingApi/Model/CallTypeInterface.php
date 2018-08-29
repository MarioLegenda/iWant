<?php

namespace App\Ebay\Presentation\FindingApi\Model;

use App\Library\Infrastructure\Helper\TypedArray;

interface CallTypeInterface
{
    /**
     * @return string
     */
    public function getOperationName(): string;
    /**
     * @return TypedArray
     */
    public function getQueries(): TypedArray;
}