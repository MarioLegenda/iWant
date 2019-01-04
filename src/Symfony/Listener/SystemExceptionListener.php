<?php

namespace App\Symfony\Listener;

use App\Library\Exception\HttpException;
use App\Library\Slack\Metadata;
use App\Library\Util\ExceptionCatchWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class SystemExceptionListener extends BaseHttpResponseListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @return JsonResponse|null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var HttpException $exception */
        $exception = $event->getException();

        if (!$exception instanceof HttpException) {
            $event->setException($exception);

            return null;
        }

        $httpInformation = $exception->getHttpInformation();

        $logMessage = sprintf(
            'An unhandled HTTP error occurred with message %s',
            $httpInformation->getBody()
        );

        $this->logger->critical($logMessage);

        $data = [
            'type' => $httpInformation->getType(),
            'message' => $logMessage,
            'url' => $httpInformation->getRequest()->getBaseUrl(),
            'external_api' => $httpInformation->getType(),
            'environment' => (string) $this->environment,
        ];

        ExceptionCatchWrapper::run(function() use ($data) {
            $this->slackClient->send(new Metadata(
                sprintf('An unhandled system exception has been caught by the %s', get_class($this)),
                '#app_activity',
                [jsonEncodeWithFix($data)]
            ));
        });

        $builtData = $this->apiSdk
            ->create($data)
            ->isError()
            ->method('GET')
            ->setStatusCode(503)
            ->isResource()
            ->build();

        $response = new JsonResponse(
            $builtData->toArray(),
            $builtData->getStatusCode()
        );

        $event->setResponse($response);
    }
}