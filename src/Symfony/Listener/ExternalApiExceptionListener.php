<?php

namespace App\Symfony\Listener;

use App\Library\Exception\HttpTransferException;
use App\Library\Exception\TransferExceptionInformationWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExternalApiExceptionListener extends BaseHttpResponseListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @return void|null
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

        $this->logger->critical($logMessage);

        $builtData = $this->apiSdk
            ->create($this->createDataForEnvironment($exceptionInformation, $logMessage))
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