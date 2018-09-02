<?php

namespace App\Ebay\Library\Model;

use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Library\Infrastructure\Helper\TypedArray;

interface FindingApiRequestModelInterface
{
    /**
     * @return CallTypeInterface
     */
    public function getCallType(): CallTypeInterface;
    /**
     * @return TypedArray
     */
    public function getItemFilters(): TypedArray;
}