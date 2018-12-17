<?php

namespace App\Library\Exception;

class HttpException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var HttpExceptionInformationWrapper $httpInformation
     */
    private $httpInformation;

    /**
     * HttpException constructor.
     * @param HttpExceptionInformationWrapper $httpExceptionInformationWrapper
     */
    public function __construct(HttpExceptionInformationWrapper $httpExceptionInformationWrapper)
    {
        parent::__construct('');
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->httpInformation->getBody();
    }
    /**
     * @return HttpExceptionInformationWrapper
     */
    public function getHttpInformation(): HttpExceptionInformationWrapper
    {
        return $this->httpInformation;
    }
}