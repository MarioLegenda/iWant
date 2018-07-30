<?php

namespace App\Library\Util;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ApiResponseData implements ArrayNotationInterface
{
    /**
     * @var array $responseData
     */
    private $responseData;
    /**
     * ApiResponseData constructor.
     * @param array $responseData
     */
    public function __construct(array $responseData)
    {
        $this->responseData = $responseData;
    }
    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->responseData['method'];
    }
    /**
     * @return bool
     */
    public function isResource(): bool
    {
        return $this->responseData['type'] === 'resource';
    }
    /**
     * @return bool
     */
    public function isCollection(): bool
    {
        return $this->responseData['type'] === 'collection';
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->responseData['statusCode'];
    }
    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->responseData[$this->responseData['type']];
    }
    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->responseData;
    }
}