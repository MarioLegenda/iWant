<?php

namespace App\Library;

use App\Library\Http\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    /**
     * @var \GuzzleHttp\Psr7\Response $guzzleResponse
     */
    private $guzzleResponse;
    /**
     * @var string $responseString
     */
    private $responseString;
    /**
     * @var int $statusCode
     */
    private $statusCode;
    /**
     * @var Request $request
     */
    private $request;
    /**
     * Response constructor.
     * @param GuzzleResponse $guzzleResponse
     * @param Request $request
     * @param string $responseString
     * @param int $statusCode
     */
    public function __construct(
        Request $request,
        string $responseString,
        int $statusCode,
        GuzzleResponse $guzzleResponse = null
    ) {
        $this->guzzleResponse = $guzzleResponse;
        $this->request = $request;
        $this->responseString = $responseString;
        $this->statusCode = $statusCode;
    }
    /**
     * @return string
     */
    public function getResponseString(): string
    {
        return $this->responseString;
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}