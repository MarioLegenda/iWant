<?php

namespace App\Symfony\Exception;

interface ImplementsExceptionBodyInterface
{
    /**
     * @return ExceptionBody
     */
    public function getBody(): ExceptionBody;
}