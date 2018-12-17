<?php

namespace App\Library\Exception;

class UnhandledSystemException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var UnhandledSystemExceptionWrapper $unhandledSystemExceptionWrapper
     */
    private $unhandledSystemExceptionWrapper;
    /**
     * UnhandledSystemException constructor.
     * @param UnhandledSystemExceptionWrapper $unhandledSystemExceptionWrapper
     */
    public function __construct(UnhandledSystemExceptionWrapper $unhandledSystemExceptionWrapper)
    {
        parent::__construct($unhandledSystemExceptionWrapper->getBody());

        $this->unhandledSystemExceptionWrapper->getBody();
    }
    /**
     * @return UnhandledSystemExceptionWrapper
     */
    public function getUnhandledSystemExceptionWrapper(): UnhandledSystemExceptionWrapper
    {
        return $this->unhandledSystemExceptionWrapper;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        $this->unhandledSystemExceptionWrapper->getBody();
    }
}