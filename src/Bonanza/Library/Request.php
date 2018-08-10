<?php

namespace App\Bonanza\Library;

class Request
{
    /**
     * @var string $baseUrl
     */
    private $baseUrl;
    /**
     * @var array $headers
     */
    private $headers;
    /**
     * @var string $data
     */
    private $data;
    /**
     * Request constructor.
     * @param string $baseUrl
     * @param array $headers
     * @param string $data
     */
    public function __construct(
        string $baseUrl,
        array $headers,
        string $data
    ) {
        $this->baseUrl = $baseUrl;
        $this->headers = $headers;
        $this->data = $data;
    }
    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
}