<?php

namespace App\Ebay\Presentation\FindingApi\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\TypedRecursion;
use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Ebay\Presentation\Model\Query;

class FindItemsAdvanced implements CallTypeInterface
{
    /**
     * @var string $operationName
     */
    private $operationName;
    /**
     * @var TypedArray|iterable|Query[]
     */
    private $queries;
    /**
     * FindItemsByKeywords constructor.
     * @param TypedArray|iterable|Query[] $queries
     */
    public function __construct(
        TypedArray $queries
    ) {
        $this->operationName = 'findItemsAdvanced';
        $this->queries = $queries;
    }
    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return $this->operationName;
    }
    /**
     * @return TypedArray
     */
    public function getQueries(): TypedArray
    {
        return $this->queries;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'operationName' => $this->getOperationName(),
            'queries' => $this->queries->toArray(TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION)
        ];
    }
}