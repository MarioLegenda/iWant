<?php

namespace App\Bonanza\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;

class BonanzaApiModel implements BonanzaApiModelInterface
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