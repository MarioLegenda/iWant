<?php

namespace App\Ebay\Source;

use App\Library\Exception\HttpTransferException;
use App\Library\Exception\TransferExceptionInformationWrapper;
use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Response\Response;
use App\Library\Http\Response\ResponseModelInterface;
use App\Library\Util\Environment;
use GuzzleHttp\Client;
use App\Library\Http\Request;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;
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
     * GenericHttpCommunicator constructor.
     * @param Environment $environment
     * @param LoggerInterface $logger
     */
    public function __construct(
        Environment $environment,
        LoggerInterface $logger
    ) {
        $this->environment = $environment;
        $this->logger = $logger;
    }
    /**
     * @inheritdoc
     */
    public function get(Request $request): ResponseModelInterface
    {
        try {
            /** @var GuzzleResponse $response */
            $response = $this->createClient()->get($request->getBaseUrl());

            return $this->createResponse($response, $request);
        } catch (
            ConnectException |
            TooManyRedirectsException $e
        ) {
            throw new HttpTransferException(
                new TransferExceptionInformationWrapper(
                    $request,
                    'eBay API',
                    'An HTTP transfer exception occurred'
                )
            );
        } catch (\Exception $e) {
            return $this->createResponse($response, $request);
        }
    }
    /**
     * @inheritdoc
     */
    public function post(Request $request): ResponseModelInterface
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
        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(Middleware::retry($this->createRetryHandler()));
        $options = [
            'timeout' => $this->getTimeout(),
            'handler' => $stack,
        ];

        return new Client($options);
    }
    /**
     * @return \Closure
     */
    private function createRetryHandler()
    {
        $logger = $this->logger;

        return function (
            $retries,
            Psr7Request $request,
            Psr7Response $response = null,
            RequestException $exception = null
        ) use ($logger) {
            $maxRetries = 2;

            if ($retries >= $maxRetries) {
                return false;
            }

            // implement response parsing on this side but only for the data
            // that the source component needs

            if ($response instanceof Psr7Response) {
                if ($response->getStatusCode() >= 200 OR $response->getStatusCode() <= 299) {
                    return false;
                }
            }

            $logger->warning(sprintf(
                'Retrying %s %s %s/%s, %s',
                $request->getMethod(),
                $request->getUri(),
                $retries + 1,
                $maxRetries,
                $response ? 'status code: ' . $response->getStatusCode() : $exception->getMessage()
            ), [$request->getHeader('Host')[0]]);
            return true;
        };
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
            'ebay_api',
            $request
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