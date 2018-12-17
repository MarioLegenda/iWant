<?php

namespace App\Library\Exception;

class UnhandledSystemExceptionWrapper
{
    /**
     * @var string $body
     */
    private $body;
    /**
     * @var string $type
     */
    private $type;
    /**
     * UnhandledSystemExceptionWrapper constructor.
     * @param string $type
     * @param string $body
     */
    public function __construct(
        string $type,
        string $body
    ) {
        $this->body = $body;
        $this->type = $type;
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
}