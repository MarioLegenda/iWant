<?php

namespace App\Library\Http;

interface GenericHttpCommunicatorInterface
{
    /**
     * @param Request $request
     * @return string
     */
    public function get(Request $request): string;
    /**
     * @param Request $request
     * @return string
     */
    public function post(Request $request): string;
}