<?php

namespace App\Library\Http;


interface GenericHttpCommunicatorInterface
{
    public function get(string $url): string;
}