<?php

namespace App\Symfony\Listener;

use App\Library\Http\Response\ResponseModelInterface;
use App\Yandex\Library\Exception\ExceptionInformationWrapper;
use App\Yandex\Library\Exception\YandexBaseException;
use App\Yandex\Library\Exception\YandexException;
use App\Yandex\Source\HttpStatusCodes;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class YandexExceptionListener extends BaseHttpResponseListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @return JsonResponse|null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var YandexException $exception */
        $exception = $event->getException();

        if (!$exception instanceof YandexBaseException) {
            $event->setException($exception);

            return null;
        }

        /** @var ExceptionInformationWrapper $exceptionInformationWrapper */
        $exceptionInformationWrapper = $exception->getExceptionInformation();
        /** @var ResponseModelInterface $response */
        $response = $exception->getExceptionInformation()->getResponse();

        $logMessage = sprintf(
            'An exception was caught with body: %s',
            $response->getBody()
        );

        $this->logger->critical($logMessage);

        $builtData = $this->apiSdk
            ->create($this->createDataForEnvironment($logMessage, $exceptionInformationWrapper))
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
     * @param ExceptionInformationWrapper $exceptionInformationWrapper
     * @return array
     */
    private function createDataForEnvironment(
        string $logMessage,
        ExceptionInformationWrapper $exceptionInformationWrapper
    ): array {
        if ((string) $this->environment === 'dev') {
            return [
                'type' => HttpStatusCodes::getName($exceptionInformationWrapper->getResponse()->getStatusCode()),
                'message' => $logMessage,
                'url' => $exceptionInformationWrapper->getResponse()->getRequest()->getBaseUrl(),
                'external_api' => 'Yandex Translation API',
                'environment' => (string) $this->environment,
            ];
        } else if ((string) $this->environment === 'prod')
        return [
            'type' => null,
            'message' => 'An exception occurred in Yandex Translation API',
            'url' => null,
            'external_api' => 'Yandex Translation API',
            'environment' => (string) $this->environment,
        ];
    }
}