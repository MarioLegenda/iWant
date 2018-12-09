<?php

namespace App\Library\Http;


use App\Library\Response;
use App\Symfony\Exception\ExternalApiNativeException;
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

class HttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @param Request $request
     * @return HttpResponse
     * @throws ExternalApiNativeException
     */
    public function get(Request $request): Response
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

    public function post(Request $request): Response
    {
        try {
            $baseUrl = $request->getBaseUrl();
            $headers = $request->getHeaders();
            $data = $request->getData();
            /** @var GuzzleResponse $response */
            $response = $this->createClient()->post($baseUrl, [
                'body' => $data,
                'headers' => $headers,
            ]);

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
        return new Client();
    }
    /**
     * @param GuzzleResponse $response
     * @param Request $request
     * @return HttpResponse
     */
    private function createResponse(
        GuzzleResponse $response,
        Request $request
    ): HttpResponse
    {
        return new HttpResponse(
            $request,
            (string) $response->getBody(),
            $response->getStatusCode(),
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
                'Accessing $client property is forbidden. User GenericHttpCommunicator::createClient() private method instead'
            );

            throw new \RuntimeException($message);
        }
    }
}