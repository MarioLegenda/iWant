<?php

namespace App\Bonanza\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class FindItemsByKeywords implements CallTypeInterface, ArrayNotationInterface
{
    /**
     * @var string $operationName
     */
    private $operationName;
    /**
     * @var string $queryName
     */
    private $queryName;
    /**
     * @var TypedArray $queryValues
     */
    private $queryValues;
    /**
     * FindItemsByKeywords constructor.
     * @param string $operationName
     * @param string $queryName
     * @param TypedArray $queryValues
     */
    public function __construct(
        string $operationName,
        string $queryName,
        TypedArray $queryValues
    ) {
        $this->operationName = $operationName;
        $this->queryName = $queryName;
        $this->queryValues = $queryValues;
    }
    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return $this->operationName;
    }
    /**
     * @return string
     */
    public function getQueryName(): string
    {
        return $this->queryName;
    }
    /**
     * @return TypedArray
     */
    public function getQueryValues(): TypedArray
    {
        return $this->queryValues;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'operationName' => $this->getOperationName(),
            'queryName' => $this->getQueryName(),
            'queryValues' => $this->getQueryValues(),
        ];
    }
}