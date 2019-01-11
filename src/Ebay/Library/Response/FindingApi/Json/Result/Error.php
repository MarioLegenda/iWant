<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

class Error
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $parameter;
    /**
     * Error constructor.
     * @param string $message
     * @param string $parameter
     */
    public function __construct(string $message, string $parameter)
    {
        $this->message = $message;
        $this->parameter = $parameter;
    }
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }
}