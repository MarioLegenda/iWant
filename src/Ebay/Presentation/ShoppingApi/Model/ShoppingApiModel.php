<?php

namespace App\Ebay\Presentation\ShoppingApi\Model;

use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Library\Infrastructure\Helper\TypedArray;

class ShoppingApiModel implements ShoppingApiRequestModelInterface
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
     * @param TypedArray|null $itemFilters
     */
    public function __construct(
        CallTypeInterface $callType,
        TypedArray $itemFilters = null
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
    /**
     * @return bool
     */
    public function hasItemFilters(): bool
    {
        return $this->itemFilters instanceof TypedArray;
    }
}