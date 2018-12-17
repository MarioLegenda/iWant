<?php

namespace App\Library\Exception;

use App\Library\Http\Request;

class TransferExceptionInformationWrapper
{
    /**
     * @var Request $request
     */
    private $request;
    /**
     * @var string $type
     */
    private $type;
    /**
     * @var string $body
     */
    private $body;
    /**
     * TransferExceptionInformationWrapper constructor.
     * @param Request $request
     * @param string $type
     * @param string $body
     */
    public function __construct(
        Request $request,
        string $type,
        string $body
    ) {
        $this->request = $request;
        $this->type = $type;
        $this->body = $body;
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}