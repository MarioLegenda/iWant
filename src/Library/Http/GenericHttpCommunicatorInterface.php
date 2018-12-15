<?php

namespace App\Library\Http;

use App\Library\Http\Response\ResponseModelInterface;

interface GenericHttpCommunicatorInterface
{
    /**
     * @param Request $request
     * @return ResponseModelInterface
     */
    public function get(Request $request): ResponseModelInterface;
    /**
     * @param Request $request
     * @return ResponseModelInterface
     */
    public function post(Request $request): ResponseModelInterface;
}