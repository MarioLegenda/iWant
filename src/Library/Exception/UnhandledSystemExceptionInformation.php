<?php

namespace App\Library\Exception;

class UnhandledSystemExceptionInformation
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
     * @param int $type
     * @param string $body
     */
    public function __construct(
        int $type,
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
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }
}