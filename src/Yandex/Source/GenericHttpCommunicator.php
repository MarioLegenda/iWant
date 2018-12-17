<?php

namespace App\Yandex\Source;

use App\Library\Exception\HttpTransferException;
use App\Library\Exception\TransferExceptionInformationWrapper;
use App\Library\Http\Request;
use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Response\ResponseModelInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use Psr\Http\Message\ResponseInterface as GuzzleResponse;
use App\Library\Http\Response\Response;

class GenericHttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @inheritdoc
     */
    public function get(Request $request): ResponseModelInterface
    {
        $message = sprintf('Method %s::%s is not implemented', get_class($this), __FUNCTION__);

        throw new \RuntimeException($message);
    }
    /**
     * @inheritdoc
     */
    public function post(Request $request): ResponseModelInterface
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
            ConnectException |
            TooManyRedirectsException $e
        ) {
            throw new HttpTransferException(
                new TransferExceptionInformationWrapper(
                    $request,
                    'Yandex Translation API',
                    'An HTTP transfer exception occurred'
                )
            );
        } catch (\Exception $e) {
            return $this->createResponse(
                $response,
                $request
            );
        }
    }
    /**
     * @return Client
     */
    private function createClient(): Client
    {
        return new Client([
            'timeout' => false,
        ]);
    }
    /**
     * @param GuzzleResponse $response
     * @param Request $request
     * @return ResponseModelInterface
     */
    private function createResponse(
        GuzzleResponse $response,
        Request $request
    ): ResponseModelInterface {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();

        return new Response(
            $statusCode,
            $body,
            'yandex_translation_api',
            $request
        );
    }
}