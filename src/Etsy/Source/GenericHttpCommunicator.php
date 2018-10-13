<?php

namespace App\Etsy\Source;

use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use App\Library\Response as HttpResponse;
use App\Symfony\Exception\ExternalApiNativeException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;

class GenericHttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @var Client $client
     */
    private $client;
    /**
     * @inheritdoc
     */
    public function get(Request $request): HttpResponse
    {
        return $this->tryGet($request);
    }
    /**
     * @inheritdoc
     */
    public function post(Request $request): HttpResponse
    {
        $message = sprintf(
            'This method is not implemented well because it uses %s on Etsy domain. Needs to be fixed',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @inheritdoc
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