<?php

namespace App\Library\Http;


use App\Bonanza\Library\Request;

interface GenericHttpCommunicatorInterface
{
    public function get(string $url): string;
    public function post(Request $request);
}