<?php

namespace App\Symfony\Listener;

use App\Ebay\Library\Exception\BaseEbayException;
use App\Ebay\Library\Exception\EbayExceptionInformation;
use App\Ebay\Library\Exception\EbayHttpException;
use App\Ebay\Library\Response\ResponseModelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class EbayExceptionListener extends BaseHttpResponseListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @return |null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var EbayHttpException $exception */
        $exception = $event->getException();

        if (!$exception instanceof BaseEbayException) {
            $event->setException($exception);

            return null;
        }

        /** @var EbayExceptionInformation $exceptionInformation */
        $exceptionInformation = $exception->getEbayExceptionInformation();
        $response = $exceptionInformation->getResponse();
        $responseModel = $exceptionInformation->getEbayResponseModel();

        $logMessage = sprintf(
            'An unhandled HTTP error occurred with message %s',
            $response->getBody()
        );

        $this->logger->critical($logMessage);

        $builtData = $this->apiSdk
            ->create($this->createDataForEnvironment($exceptionInformation, $responseModel, $logMessage))
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

    private function createDataForEnvironment(
        EbayExceptionInformation $ebayExceptionInformation,
        ResponseModelInterface $responseModel,
        string $logMessage
    ) {
        if ((string) $this->environment === 'prod') {
            return [
                'type' => $ebayExceptionInformation->getType(),
                'message' => null,
                'url' => null,
                'external_api' => 'eBay',
                'environment' => (string) $this->environment,
                'data' => null
            ];
        } else if ((string) $this->environment === 'dev') {
            return [
                'type' => $ebayExceptionInformation->getType(),
                'message' => $logMessage,
                'url' => $ebayExceptionInformation->getRequest()->getBaseUrl(),
                'external_api' => 'eBay',
                'environment' => (string) $this->environment,
                'data' => $responseModel->getErrors()->toArray()
            ];
        }
    }
}