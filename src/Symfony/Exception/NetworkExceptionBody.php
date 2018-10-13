<?php

namespace App\Symfony\Exception;

class NetworkExceptionBody extends ExceptionBody
{
    /**
     * NetworkExceptionBody constructor.
     * @param int $statusCode
     * @param array $data
     */
    public function __construct(
        int $statusCode,
        array $data
    ) {
        parent::__construct($statusCode);

        $this->body = $data;
    }
}