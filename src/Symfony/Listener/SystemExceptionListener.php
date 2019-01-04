<?php

namespace App\Symfony\Listener;

use App\Library\Exception\HttpException;
use App\Library\Http\Response\ApiSDK;
use App\Library\Util\Environment;
use App\Library\Util\SlackImplementation;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class SystemExceptionListener
{
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var SlackImplementation $slackImplementation
     */
    private $slackImplementation;
    /**
     * SystemExceptionListener constructor.
     * @param Environment $environment
     * @param LoggerInterface $logger
     * @param ApiSDK $apiSDK
     * @param SlackImplementation $slackImplementation
     */
    public function __construct(
        Environment $environment,
        LoggerInterface $logger,
        ApiSDK $apiSDK,
        SlackImplementation $slackImplementation
    ) {
        $this->logger = $logger;
        $this->apiSdk = $apiSDK;
        $this->environment = $environment;
        $this->slackImplementation = $slackImplementation;
    }
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

        $builtData = $this->apiSdk
            ->create([
                'type' => $httpInformation->getType(),
                'message' => $logMessage,
                'url' => $httpInformation->getRequest()->getBaseUrl(),
                'external_api' => $httpInformation->getType(),
                'environment' => (string) $this->environment,
            ])
            ->isError()
            ->method('GET')
            ->setStatusCode(503)
            ->isResource()
            ->build();

        return new JsonResponse(
            $builtData->toArray(),
            $builtData->getStatusCode()
        );
    }
}