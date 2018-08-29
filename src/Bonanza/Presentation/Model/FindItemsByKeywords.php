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
     * @var string $queryValue
     */
    private $queryValue;
    /**
     * FindItemsByKeywords constructor.
     * @param string $operationName
     * @param string $queryName
     * @param string $queryValue
     */
    public function __construct(
        string $operationName,
        string $queryName,
        string $queryValue
    ) {
        $this->operationName = $operationName;
        $this->queryName = $queryName;
        $this->queryValue = $queryValue;
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
     * @return string
     */
    public function getQueryValue(): string
    {
        return $this->queryValue;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'operationName' => $this->getOperationName(),
            'queryName' => $this->getQueryName(),
            'queryValue' => $this->getQueryValue(),
        ];
    }
}