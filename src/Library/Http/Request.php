<?php

namespace App\Library\Http;

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
     * @var mixed $data
     */
    private $data;
    /**
     * Request constructor.
     * @param string $baseUrl
     * @param iterable|null $headers
     * @param $data|null
     * @param \Closure|null $dataConverter
     */
    public function __construct(
        string $baseUrl,
        iterable $headers = null,
        $data = null,
        \Closure $dataConverter = null
    ) {
        if ($dataConverter instanceof \Closure) {
            $data = $dataConverter->__invoke($baseUrl, $headers, $data);
        }

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
    public function getHeaders(): ?array
    {
        return $this->headers;
    }
    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}