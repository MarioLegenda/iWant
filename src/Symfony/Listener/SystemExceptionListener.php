<?php

namespace App\Symfony\Listener;

use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Util\Environment;
use App\Library\Util\SlackImplementation;
use App\Symfony\Exception\ExceptionType;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @throws \Http\Client\Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->logger->critical($event->getException()->getMessage());

        /** @var ApiResponseData $builtData */
        $builtData = $this->apiSdk
            ->create([
                'type' => ExceptionType::SYSTEM_EXCEPTION,
                'message' => 'A system exception occurred',
            ])
            ->method(strtoupper($event->getRequest()->getMethod()))
            ->isError()
            ->setStatusCode(500)
            ->isResource()
            ->build();

        $this->slackImplementation->sendMessageToChannel(
            '#http_exceptions',
            sprintf(
                'An error occurred with message: %s. The actual response sent to the server: %s. Stack trace: %s',
                $event->getException()->getMessage(),
                json_encode($builtData->toArray()),
                $event->getException()->getTraceAsString()
            )
        );

        $response = $event->getResponse();

        if ($response instanceof Response) {
            $response->setContent($builtData->toArray());
            $response->setStatusCode($builtData->getStatusCode());

            $event->setResponse($response);

            return;
        }

        $response = new JsonResponse(
            $builtData->toArray(),
            $builtData->getStatusCode()
        );

        $event->setResponse($response);
    }
}