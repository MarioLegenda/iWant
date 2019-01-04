<?php

namespace App\Library\Slack;

use App\Library\Http\HttpCommunicator;
use App\Library\Http\Request;

class SlackClient
{
    /**
     * @var Config $config
     */
    private $config;
    /**
     * @var HttpCommunicator $httpCommunicator
     */
    private $httpCommunicator;
    /**
     * Client constructor.
     * @param Config $config
     * @param HttpCommunicator $httpCommunicator
     */
    public function __construct(
        Config $config,
        HttpCommunicator $httpCommunicator
    ) {
        $this->httpCommunicator = $httpCommunicator;
        $this->config = $config;
    }
    /**
     * @param Metadata $metadata
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function send(Metadata $metadata): void
    {
        $request = new Request(
            $this->config->getWebhook($metadata->getChannel()),
            null,
            jsonEncodeWithFix(['text' => (string) $metadata])
        );

        $this->httpCommunicator->post($request);
    }
}