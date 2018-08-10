<?php

namespace App\Etsy\Source;

use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Request;
use GuzzleHttp\Client;

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
            'This method is not implemented well because it uses %s on Etsy domain. Needs to be fixed',
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