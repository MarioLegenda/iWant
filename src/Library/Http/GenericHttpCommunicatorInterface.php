<?php

namespace App\Library\Http;

use App\Library\Response;

interface GenericHttpCommunicatorInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function get(Request $request): Response;
    /**
     * @param Request $request
     * @return Response
     */
    public function post(Request $request): Response;
}