<?php

namespace App\Ebay\Source;

use App\Library\Http\GenericHttpCommunicatorInterface;
use GuzzleHttp\Client;
use App\Library\Http\Request;

class GenericHttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @var Client $client
     */
    private $client;
    /**
     * @param Request $request
     * @return string
     */
    public function get(Request $request): string
    {
        return $this->tryGet($request);
    }
    /**
     * @param Request $request
     * @return string
     */
    public function post(Request $request): string
    {
        $message = sprintf(
            '%s does not implement %s:post() method',
            get_class($this),
            get_class($this)
        );

        throw new \RuntimeException($message);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function tryGet(Request $request): string
    {
        try {
            $response = (string) $this->createClient()->get($request->getBaseUrl())->getBody();
        } catch (\Exception $e) {
            $response = (string) $e->getResponse()->getBody()->getContents();
        }

        return $response;
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