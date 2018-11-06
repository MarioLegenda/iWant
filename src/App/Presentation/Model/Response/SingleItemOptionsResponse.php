<?php

namespace App\App\Presentation\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SingleItemOptionsResponse implements ArrayNotationInterface
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var string $method
     */
    private $method;
    /**
     * @var string $route
     */
    private $route;
    /**
     * SingleItemOptionsResponse constructor.
     * @param string $method
     * @param string $route
     * @param string $itemId
     */
    public function __construct(
        string $method,
        string $route,
        string $itemId
    ) {
        $this->itemId = $itemId;
        $this->method = $method;
        $this->route = $route;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'method' => $this->getMethod(),
            'route' => $this->getRoute(),
        ];
    }
}