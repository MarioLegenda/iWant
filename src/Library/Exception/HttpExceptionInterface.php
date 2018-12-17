<?php

namespace App\Library\Exception;

interface HttpExceptionInterface
{
    /**
     * @return string
     */
    public function getBody(): string;
}