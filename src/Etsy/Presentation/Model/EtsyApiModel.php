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
     * @var TypedArray|null $queries
     */
    private $queries;
    /**
     * EtsyApiModel constructor.
     * @param TypeInterface $methodType
     * @param TypedArray $itemFilters
     * @param TypedArray|null $queries
     */
    public function __construct(
        TypeInterface $methodType,
        TypedArray $itemFilters,
        TypedArray $queries = null
    ) {
        $this->methodType = $methodType;
        $this->itemFilters = $itemFilters;
        $this->queries = $queries;
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
    /**
     * @return TypedArray
     */
    public function getQueries(): ?TypedArray
    {
        return $this->queries;
    }


}