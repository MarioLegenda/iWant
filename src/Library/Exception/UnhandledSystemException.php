<?php

namespace App\Library\Exception;

class UnhandledSystemException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var UnhandledSystemExceptionInformation $unhandledSystemExceptionWrapper
     */
    private $unhandledSystemExceptionWrapper;
    /**
     * UnhandledSystemException constructor.
     * @param UnhandledSystemExceptionInformation $unhandledSystemExceptionWrapper
     */
    public function __construct(UnhandledSystemExceptionInformation $unhandledSystemExceptionWrapper)
    {
        parent::__construct($unhandledSystemExceptionWrapper->getBody());

        $this->unhandledSystemExceptionWrapper->getBody();
    }
    /**
     * @return UnhandledSystemExceptionInformation
     */
    public function getUnhandledSystemExceptionWrapper(): UnhandledSystemExceptionInformation
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