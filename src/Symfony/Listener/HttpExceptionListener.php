<?php

namespace App\Symfony\Listener;


use App\Symfony\Exception\ImplementsExceptionBodyInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->logger->log('error', $event->getException()->getMessage());

        /** @var ImplementsExceptionBodyInterface $exception */
        $exception = $event->getException();

        if (!$exception instanceof ImplementsExceptionBodyInterface) {
            return;
        }

        /** @var Response $response */
        $response = $event->getResponse();

        if (!$response instanceof JsonResponse) {
            $event->setResponse(new JsonResponse(
                $exception->getBody()->toArray(),
                $exception->getBody()->getStatusCode()
            ));

            return;
        }

        $response->setContent(json_encode($exception->getBody()->toArray()));
        $response->setStatusCode($exception->getBody()->getStatusCode());

        $event->setResponse($response);
    }
}