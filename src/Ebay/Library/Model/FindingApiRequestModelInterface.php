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
     * @return TypedArray|iterable
     */
    public function getItemFilters(): iterable;
    /**
     * @return bool
     */
    public function hasItemFilters(): bool;
}