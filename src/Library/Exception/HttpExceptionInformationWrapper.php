<?php

namespace App\Library\Exception;

use App\Library\Http\Request;

class HttpExceptionInformationWrapper
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
     * @var string $type
     */
    private $type;
    /**
     * @var Request $request
     */
    private $request;
    /**
     * HttpExceptionInformationWrapper constructor.
     * @param Request $request
     * @param int $statusCode
     * @param string $type
     * @param string $body
     */
    public function __construct(
        Request $request,
        int $statusCode,
        string $type,
        string $body
    ) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->type = $type;
        $this->request = $request;
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}