<?php

namespace App\Symfony\Exception;


use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ExternalApiNativeException extends \Exception implements ImplementsExceptionBodyInterface
{
    /**
     * @var ExceptionBody $body
     */
    private $body;
    /**
     * ExternalApiNativeException constructor.
     * @param ArrayNotationInterface $body
     */
    public function __construct(ArrayNotationInterface $body)
    {
        $this->body = $body;
    }
    /**
     * @return ExceptionBody
     */
    public function getBody(): ExceptionBody
    {
        return $this->body;
    }
}