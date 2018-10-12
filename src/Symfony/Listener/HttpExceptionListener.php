<?php

namespace App\Symfony\Listener;


use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class HttpExceptionListener
{
    public function __construct()
    {
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

    }
}