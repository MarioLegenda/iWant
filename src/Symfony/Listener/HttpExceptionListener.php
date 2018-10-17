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
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getRequest()->isXmlHttpRequest()) {
            $this->logger->critical(sprintf(
                'Non ajax request caught with the exception handler. Passing it trough. Message %s',
                $event->getException()->getMessage()
            ));

            throw $event->getException();
        }

        /** @var ImplementsExceptionBodyInterface $exception */
        $exception = $event->getException();

        if (!$exception instanceof ImplementsExceptionBodyInterface) {
            $this->logger->critical(sprintf(
                'Http error detected with json message body: %s',
                json_encode($event->getException()->getMessage())
            ));

            return;
        }

        $this->logger->critical(sprintf(
            'Http error detected with json message body: %s',
            json_encode($event->getException()->getBody()->toArray())
        ));

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