<?php

namespace App\Library\Http\Response;

use App\Library\Http\Request;

class Response implements ResponseModelInterface
{
    /**
     * @var int $statusCode
     */
    private $statusCode;
    /**
     * @var string $body
     */
    private $body;
    /**
     * @var string $apiIdentifier
     */
    private $apiIdentifier;
    /**
     * @var Request $request
     */
    private $request;
    /**
     * Response constructor.
     * @param int $statusCode
     * @param string $body
     * @param string $apiIdentifier
     * @param Request $request
     */
    public function __construct(
        int $statusCode,
        string $body,
        string $apiIdentifier,
        Request $request
    ) {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->apiIdentifier = $apiIdentifier;
        $this->request = $request;
    }
    /**
     * @return string
     */
    public function getApiIdentifier(): string
    {
        return $this->apiIdentifier;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
    /**
     * @return array
     */
    public function getBodyArrayIfJson(): ?array
    {
        return json_decode($this->getBody(), true);
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * @return bool
     */
    public function is200Range(): bool
    {
        return $this->getStatusCode() >= 200 AND $this->getStatusCode() <= 299;
    }
    /**
     * @return bool
     */
    public function is500Range(): bool
    {
        return $this->getStatusCode() >= 500 AND $this->getStatusCode() <= 599;
    }
    /**
     * @return bool
     */
    public function is400Range(): bool
    {
        return $this->getStatusCode() >= 400 AND $this->getStatusCode() <= 499;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}