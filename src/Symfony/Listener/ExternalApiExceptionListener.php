<?php

namespace App\Symfony\Listener;

use App\Library\Exception\HttpTransferException;
use App\Library\Exception\TransferExceptionInformationWrapper;
use App\Library\Slack\Metadata;
use App\Library\Util\ExceptionCatchWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExternalApiExceptionListener extends BaseHttpResponseListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @return void|null
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getRequest()->isXmlHttpRequest()) {
            $this->logger->critical(sprintf(
                'Non ajax request caught with the exception handler. Passing it trough. Message %s',
                $event->getException()->getMessage()
            ));

            return;
        }

        /** @var HttpTransferException $exception */
        $exception = $event->getException();

        if (!$exception instanceof HttpTransferException) {
            $event->setException($exception);

            return null;
        }

        /** @var TransferExceptionInformationWrapper $exceptionInformation */
        $exceptionInformation = $exception->getTransferInformationWrapper();

        $logMessage = sprintf(
            'An exception was caught of type %s with body: %s',
            $exceptionInformation->getType(),
            $exceptionInformation->getBody()
        );

        $data = $this->createDataForEnvironment($exceptionInformation, $logMessage);

        $this->logger->critical($logMessage);

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

        $event->setResponse(new JsonResponse(
            $builtData->toArray(),
            $builtData->getStatusCode()
        ));
    }
    /**
     * @param TransferExceptionInformationWrapper $exceptionInformationWrapper
     * @param string $logMessage
     * @return array
     */
    private function createDataForEnvironment(
        TransferExceptionInformationWrapper $exceptionInformationWrapper,
        string $logMessage
    ) {
        if ((string) $this->environment === 'prod') {
            return [
                'type' => $exceptionInformationWrapper->getType(),
                'message' => $logMessage,
                'url' => $exceptionInformationWrapper->getRequest()->getBaseUrl(),
                'external_api' => sprintf('External api of type %s', $exceptionInformationWrapper->getType()),
                'environment' => (string) $this->environment,
            ];
        } else if ((string) $this->environment === 'dev') {
            return [
                'type' => $exceptionInformationWrapper->getType(),
                'message' => $logMessage,
                'url' => null,
                'external_api' => sprintf('External api of type %s', $exceptionInformationWrapper->getType()),
                'environment' => (string) $this->environment,
            ];
        }
    }
}