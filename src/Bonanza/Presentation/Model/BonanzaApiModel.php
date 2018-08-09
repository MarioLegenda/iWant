<?php

namespace App\Bonanza\Presentation\Model;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;

class BonanzaApiModel implements FindingApiRequestModelInterface
{
    /**
     * @var CallTypeInterface $callType
     */
    private $callType;
    /**
     * @var TypedArray $itemFilters
     */
    private $itemFilters;
    /**
     * FindingApiModel constructor.
     * @param CallTypeInterface $callType
     * @param TypedArray $itemFilters
     */
    public function __construct(
        CallTypeInterface $callType,
        TypedArray $itemFilters
    ) {
        $this->callType = $callType;
        $this->itemFilters = $itemFilters;
    }

    /**
     * @return CallTypeInterface
     */
    public function getCallType(): CallTypeInterface
    {
        return $this->callType;
    }
    /**
     * @return TypedArray
     */
    public function getItemFilters(): TypedArray
    {
        return $this->itemFilters;
    }
}