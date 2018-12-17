<?php

namespace App\Yandex\Library\Exception;

use App\Library\Exception\HttpExceptionInterface;

class YandexException extends YandexBaseException implements HttpExceptionInterface
{
    /**
     * @var ExceptionInformationWrapper $exceptionInformation
     */
    private $exceptionInformation;
    /**
     * YandexException constructor.
     * @param ExceptionInformationWrapper $exceptionInformation
     */
    public function __construct(ExceptionInformationWrapper $exceptionInformation)
    {
        parent::__construct($exceptionInformation->getResponse()->getBody());

        $this->exceptionInformation = $exceptionInformation;
    }
    /**
     * @return ExceptionInformationWrapper
     */
    public function getExceptionInformation(): ExceptionInformationWrapper
    {
        return $this->exceptionInformation;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->exceptionInformation->getResponse()->getBody();
    }
}