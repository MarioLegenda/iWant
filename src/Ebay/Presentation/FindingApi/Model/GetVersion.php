<?php

namespace App\Ebay\Presentation\FindingApi\Model;

use App\Ebay\Presentation\Model\CallTypeInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class GetVersion implements CallTypeInterface, ArrayNotationInterface
{
    /**
     * @var TypedArray|null $queries
     */
    private $queries;
    /**
     * @var string $operationName
     */
    private $operationName;
    /**
     * GetVersion constructor.
     * @param TypedArray|null $queries
     */
    public function __construct(
        TypedArray $queries = null
    ) {
        $this->operationName = 'getVersion';
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
        $message = sprintf(
            '%s::%s is not implemented and should not be used',
            get_class($this),
            __FUNCTION__
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return bool
     */
    public function hasQueries(): bool
    {
        return $this->queries instanceof TypedArray;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'operationName' => $this->getOperationName(),
            'queries' => [],
        ];
    }
}