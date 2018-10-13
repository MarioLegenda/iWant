<?php

namespace App\Symfony\Listener;

use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Util\Environment;
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
     * SystemExceptionListener constructor.
     * @param Environment $environment
     * @param LoggerInterface $logger
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        Environment $environment,
        LoggerInterface $logger,
        ApiSDK $apiSDK
    ) {
        $this->logger = $logger;
        $this->apiSdk = $apiSDK;
        $this->environment = $environment;
    }
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->logger->log('error', $event->getException()->getMessage());

        dump($event->getResponse()->getContent());
        die();
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