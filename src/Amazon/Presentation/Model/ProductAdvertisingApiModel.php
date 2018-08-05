<?php

namespace App\Amazon\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Type\TypeInterface;

class ProductAdvertisingApiModel
{
    /**
     * @var TypeInterface
     */
    private $operationType;
    /**
     * @var TypedArray $itemFilters
     */
    private $itemFilters;
    /**
     * ProductAdvertisingApiModel constructor.
     * @param TypeInterface $operationType
     * @param TypedArray $itemFilters
     */
    public function __construct(
        TypeInterface $operationType,
        TypedArray $itemFilters
    ) {
        $this->operationType = $operationType;
        $this->itemFilters = $itemFilters;
    }


}