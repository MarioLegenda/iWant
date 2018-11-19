<?php

namespace App\Ebay\Source;

use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Util\Environment;
use App\Symfony\Exception\ExceptionType;
use App\Symfony\Exception\NetworkExceptionBody;
use GuzzleHttp\Client;
use App\Library\Http\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use App\Library\Response as HttpResponse;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;
use App\Symfony\Exception\ExternalApiNativeException;
use Http\Client\Exception\NetworkException;
use Psr\Log\LoggerInterface;

class GenericHttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * GenericHttpCommunicator constructor.
     * @param Environment $environment
     * @param LoggerInterface $logger
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        LoggerInterface $logger,
        Environment $environment,
        ApiSDK $apiSDK
    ) {
        $this->environment = $environment;
        $this->apiSdk = $apiSDK;
        $this->logger = $logger;
    }
    /**
     * @var Client $client
     */
    private $client;
    /**
     * @inheritdoc
     */
    public function get(Request $request): HttpResponse
    {
        try {
            /** @var GuzzleResponse $response */
            $response = $this->createClient()->get($request->getBaseUrl());

            return $this->createResponse($response, $request);
        } catch (
        ServerException |
        RequestException |
        BadResponseException |
        NetworkException |
        ClientException  |
        ConnectException |
        SeekException |
        TooManyRedirectsException |
        TransferException $e) {
            $message = 'A network problem on the Ebay external api has been detected';
            if ((string) $this->environment === 'dev' OR (string) $this->environment === 'test') {
                $message = $e->getMessage();

                $exceptionMessage = $e->getMessage();
                $logMessage = sprintf(
                    'An exception was caught in dev/test environment with message: %s',
                    $exceptionMessage
                );

                $this->logger->critical($logMessage);
            }

            /** @var ApiResponseData $builtData */
            $builtData = $this->apiSdk
                ->create([
                    'type' => ExceptionType::HTTP_EXCEPTION,
                    'message' => $message,
                    'url' => $request->getBaseUrl(),
                    'external_api' => 'ebay',
                    'environment' => (string) $this->environment,
                ])
                ->isError()
                ->method('GET')
                ->setStatusCode(503)
                ->isResource()
                ->build();

            $networkExceptionBody = new NetworkExceptionBody($builtData->getStatusCode(), $builtData->toArray());

            throw new ExternalApiNativeException($networkExceptionBody);
        } catch (\Exception $e) {
            $message = 'An unhandled exception has been detected in the Ebay api';
            if ((string) $this->environment === 'dev' OR (string) $this->environment === 'test') {
                $message = $e->getMessage();

                $exceptionMessage = $e->getMessage();
                $logMessage = sprintf(
                    'An exception was caught in dev/test environment with message: %s',
                    $exceptionMessage
                );

                $this->logger->critical($logMessage);
            }

            /** @var ApiResponseData $builtData */
            $builtData = $this->apiSdk
                ->create([
                    'type' => ExceptionType::HTTP_EXCEPTION,
                    'message' => $message,
                    'url' => $request->getBaseUrl(),
                    'external_api' => 'ebay',
                ])
                ->method('GET')
                ->isError()
                ->setStatusCode(503)
                ->isResource()
                ->build();

            $networkExceptionBody = new NetworkExceptionBody($builtData->getStatusCode(), $builtData->toArray());

            throw new ExternalApiNativeException($networkExceptionBody);
        }
    }
    /**
     * @inheritdoc
     */
    public function post(Request $request): HttpResponse
    {
        $message = sprintf(
            '%s does not implement %s:post() method',
            get_class($this),
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return Client
     */
    private function createClient(): Client
    {
        $options = [
            'timeout' => $this->getTimeout(),
        ];

        if (!$this->client instanceof Client) {
            $this->client = new Client($options);
        }

        return $this->client;
    }
    /**
     * @param $name
     */
    public function __get($name)
    {
        if ($name === 'client') {
            $message = sprintf(
                'Accessing $client property is forbidden. User GenericHttpCommunicator::createClient() private method instead'
            );

            throw new \RuntimeException($message);
        }
    }
    /**
     * @param GuzzleResponse $response
     * @param Request $request
     * @return HttpResponse
     */
    private function createResponse(
        GuzzleResponse $response,
        Request $request
    ): HttpResponse {
        return new HttpResponse(
            $request,
            (string) $response->getBody(),
            200,
            $response
        );
    }
    /**
     * @return int
     */
    private function getTimeout()
    {
        $timeout = 60;

        if ((string) $this->environment === 'dev') {
            $timeout = 60;
        }

        if ((string) $this->environment === 'test') {
            $timeout = false;
        }

        return $timeout;
    }
}