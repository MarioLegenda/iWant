<?php

namespace App\Bonanza\Source;

use App\Bonanza\Library\Request;
use App\Library\Http\GenericHttpCommunicatorInterface;
use GuzzleHttp\Client;

class GenericHttpCommunicator implements GenericHttpCommunicatorInterface
{
    /**
     * @var Client $client
     */
    private $client;
    /**
     * @param string $url
     * @return string
     */
    public function get(string $url): string
    {
        return $this->tryGet($url);
    }

    /**
     * @param Request $request
     */
    public function post(Request $request)
    {
        $baseUrl = $request->getBaseUrl();
        $headers = $request->getHeaders();
        $data = $request->getData();

        $response = $this->createClient()->post($baseUrl, [
            'body' => $data,
            'headers' => $headers,
        ]);
    }
    /**
     * @param string $url
     * @return string
     */
    private function tryGet(string $url): string
    {
        try {
            $response = (string) $this->createClient()->get($url)->getBody();
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