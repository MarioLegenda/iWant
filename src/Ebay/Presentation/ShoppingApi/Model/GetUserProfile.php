<?php

namespace App\Ebay\Presentation\ShoppingApi\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\TypedRecursion;
use App\Ebay\Presentation\Model\CallTypeInterface;

class GetUserProfile implements CallTypeInterface, ArrayNotationInterface
{
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
        $this->queries = $queries;
    }
    /**
     * @return string
     */
    public function getOperationName(): string
    {
        $message = sprintf(
            '%s::getOperationName() is not supported because it is ok to use just queries with callname query name/value type',
            get_class($this)
        );

        throw new \RuntimeException($message);
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