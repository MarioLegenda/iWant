<?php

namespace App\Amazon\Presentation\ProductAdvertising\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Type\TypeInterface;

class ProductAdvertisingApiModel
{
    /**
     * @var TypeInterface $operation
     */
    private $operation;
    /**
     * @var TypedArray $itemFilters
     */
    private $itemFilters;
    /**
     * ProductAdvertisingApiModel constructor.
     * @param TypeInterface $type
     * @param TypedArray $itemFilters
     */
    public function __construct(
        TypeInterface $type,
        TypedArray $itemFilters
    ) {
        $this->operation = $type;
        $this->itemFilters = $itemFilters;
    }
    /**
     * @return TypeInterface
     */
    public function getOperation(): TypeInterface
    {
        return $this->operation;
    }
    /**
     * @return TypedArray
     */
    public function getItemFilters(): TypedArray
    {
        return $this->itemFilters;
    }
}