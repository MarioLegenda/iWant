<?php

namespace App\Component\Search\Ebay\Model\Response;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\TypedRecursion;

class EbaySiteSearchResponseModel implements ArrayNotationInterface
{
    /**
     * @var string $globalId
     */
    private $globalId;
    /**
     * @var array $globalIdInformation
     */
    private $globalIdInformation;
    /**
     * @var TypedArray $items
     */
    private $items;
    /**
     * EbaySiteSearchResponseModel constructor.
     * @param string $globalId
     * @param array $globalIdInformation
     * @param TypedArray $items
     */
    public function __construct(
        string $globalId,
        array $globalIdInformation,
        TypedArray $items
    ) {
        $this->globalId = $globalId;
        $this->globalIdInformation = $globalIdInformation;
        $this->items = $items;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
    /**
     * @return array
     */
    public function getGlobalIdInformation(): array
    {
        return $this->globalIdInformation;
    }
    /**
     * @return TypedArray
     */
    public function getItems(): TypedArray
    {
        return $this->items;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'globalId' => $this->getGlobalId(),
            'globalIdInformation' => $this->getGlobalIdInformation(),
            'items' => $this->items->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION),
        ];
    }
}