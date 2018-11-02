<?php

namespace App\Yandex\Source;

use App\Library\Http\Request;
use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Response;
use App\Library\Util\Environment;
use App\Symfony\Exception\ExceptionType;
use App\Symfony\Exception\ExternalApiNativeException;
use App\Symfony\Exception\NetworkExceptionBody;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use App\Library\Response as HttpResponse;
use Psr\Log\LoggerInterface;

class GenericHttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * GenericHttpCommunicator constructor.
     * @param Environment $environment
     * @param LoggerInterface $logger
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        Environment $environment,
        LoggerInterface $logger,
        ApiSDK $apiSDK
    ) {
        $this->environment = $environment;
        $this->logger = $logger;
        $this->apiSdk = $apiSDK;
    }
    /**
     * @var Client $client
     */
    private $client;
    /**
     * @inheritdoc
     */
    public function get(Request $request): Response
    {
        return $this->tryGet($request);
    }
    /**
     * @inheritdoc
     */
    public function post(Request $request): Response
    {
        $baseUrl = $request->getBaseUrl();
        $headers = $request->getHeaders();
        $data = $request->getData();

        try {
            /** @var GuzzleResponse $response */
            $response = $this->createClient()->post($baseUrl, [
                'body' => $data,
                'headers' => $headers,
            ]);

            return $this->createResponse(
                $response,
                $request
            );
        } catch (
            ServerException |
            RequestException |
            BadResponseException |
            ClientException  |
            ConnectException |
            SeekException |
            TooManyRedirectsException |
            TransferException $e) {

            $message = 'A network problem on the Yandex external api has been detected';
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
            $message = 'An unhandled exception has been detected in the Yandex api';
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
     * @param Request $request
     * @return HttpResponse
     * @throws ExternalApiNativeException
     */
    private function tryGet(Request $request): HttpResponse
    {
        try {
            /** @var GuzzleResponse $response */
            $response = $this->createClient()->get($request->getBaseUrl());

            return $this->createResponse($response, $request);
        } catch (
            ServerException |
            RequestException |
            BadResponseException |
            ClientException  |
            ConnectException |
            SeekException |
            TooManyRedirectsException |
            TransferException $e) {

            throw new ExternalApiNativeException($e);
        } catch (\Exception $e) {
            throw new ExternalApiNativeException($e);
        }
    }
    /**
     * @return Client
     */
    private function createClient(): Client
    {
        if (!$this->client instanceof Client) {
            $this->client = new Client([
                'timeout' => false,
            ]);
        }

        return $this->client;
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
        $responseContent = (string) $response->getBody();
        $statusCode = $response->getStatusCode();

        return new HttpResponse(
            $request,
            $responseContent,
            $statusCode,
            $response
        );
    }
    /**
     * @param $name
     */
    public function __get($name)
    {
        if ($name === 'client') {
            $message = sprintf(
                'Accessing $client property is forbidden. Use GenericHttpCommunicator::createClient() private method instead'
            );

            throw new \RuntimeException($message);
        }
    }
}