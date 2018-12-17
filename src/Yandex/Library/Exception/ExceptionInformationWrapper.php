<?php

namespace App\Yandex\Library\Exception;

use App\Library\Http\Response\ResponseModelInterface;

class ExceptionInformationWrapper
{
    /**
     * @var ResponseModelInterface $response
     */
    private $response;
    /**
     * ExceptionInformationWrapper constructor.
     * @param ResponseModelInterface $response
     */
    public function __construct(ResponseModelInterface $response)
    {
        $this->response = $response;
    }
    /**
     * @return ResponseModelInterface
     */
    public function getResponse(): ResponseModelInterface
    {
        return $this->response;
    }
}