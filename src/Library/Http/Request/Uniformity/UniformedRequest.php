<?php

namespace App\Library\Http\Request\Uniformity;

use App\Library\Infrastructure\Type\TypeInterface;

class UniformedRequest
{
    /**
     * @var TypeInterface $internalType
     */
    private $internalType;
    /**
     * @var TypeInterface $method
     */
    private $method;
    /**
     * @var iterable $data
     */
    private $data;
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * @var string $baseUrl
     */
    private $baseUrl;
    /**
     * @var string $route
     */
    private $route;
    /**
     * UniformedRequest constructor.
     * @param TypeInterface $internalType
     * @param TypeInterface $method
     * @param iterable $data
     * @param string $uniqueName
     * @param string $baseUrl
     * @param string $route
     */
    public function __construct(
        TypeInterface $internalType,
        TypeInterface $method,
        iterable $data,
        string $uniqueName,
        string $baseUrl,
        string $route
    ) {
        $this->internalType = $internalType;
        $this->method = $method;
        $this->data = $data;
        $this->uniqueName = $uniqueName;
    }

    /**
     * @return TypeInterface
     */
    public function getInternalType(): TypeInterface
    {
        return $this->internalType;
    }
    /**
     * @return TypeInterface
     */
    public function getMethod(): TypeInterface
    {
        return $this->method;
    }
    /**
     * @return iterable
     */
    public function getData(): iterable
    {
        return $this->data;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }
    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }
}