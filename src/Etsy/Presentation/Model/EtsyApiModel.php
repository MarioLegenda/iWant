<?php

namespace App\Etsy\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Type\TypeInterface;

class EtsyApiModel
{
    /**
     * @var TypeInterface $methodType
     */
    private $methodType;
    /**
     * @var TypedArray $itemFilters
     */
    private $itemFilters;
    /**
     * EtsyApiModel constructor.
     * @param TypeInterface $methodType
     * @param TypedArray $itemFilters
     */
    public function __construct(
        TypeInterface $methodType,
        TypedArray $itemFilters
    ) {
        $this->methodType = $methodType;
        $this->itemFilters = $itemFilters;
    }
    /**
     * @return TypeInterface
     */
    public function getMethodType(): TypeInterface
    {
        return $this->methodType;
    }
    /**
     * @return TypedArray
     */
    public function getItemFilters(): TypedArray
    {
        return $this->itemFilters;
    }


}