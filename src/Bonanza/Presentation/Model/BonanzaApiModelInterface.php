<?php

namespace App\Bonanza\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;

interface BonanzaApiModelInterface
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