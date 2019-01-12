<?php

namespace App\Symfony\Listener;

use App\Library\Slack\Metadata;
use App\Library\Util\ExceptionCatchWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class EmptyEbayResultException extends BaseHttpResponseListener
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

        /** @var EmptyEbayResultException|\Exception $exception */
        $exception = $event->getException();

        if (!$exception instanceof EmptyEbayResultException) {
            $event->setException($exception);

            return null;
        }

        $logMessage = sprintf(
            'An empty result from ebay exception was caught of type %s with body: %s',
            get_class($exception),
            $exception->getMessage()
        );

        $data = $this->createDataForEnvironment($logMessage);

        $this->logger->critical($logMessage);

        ExceptionCatchWrapper::run(function() use ($data, $logMessage) {
            $this->slackClient->send(new Metadata(
                sprintf('An empty result from ebay with model %s', $logMessage),
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
     * @param string $logMessage
     * @return array
     */
    private function createDataForEnvironment(
        string $logMessage
    ) {
        if ((string) $this->environment === 'prod') {
            return [
                'type' => '',
                'message' => $logMessage,
                'url' => '',
                'external_api' => 'eBay',
                'environment' => (string) $this->environment,
            ];
        } else if ((string) $this->environment === 'dev') {
            return [
                'type' => 'EMPTY_EBAY_RESULT',
                'message' => $logMessage,
                'url' => null,
                'external_api' => 'eBay',
                'environment' => (string) $this->environment,
            ];
        }
    }
}