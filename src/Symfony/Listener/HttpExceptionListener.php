<?php

namespace App\Symfony\Listener;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class HttpExceptionListener
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * HttpExceptionListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

    }
}