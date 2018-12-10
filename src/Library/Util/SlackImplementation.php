<?php

namespace App\Library\Util;

use App\Library\Slack\Metadata;
use App\Library\Slack\SlackClient;
use Psr\Log\LoggerInterface;

class SlackImplementation
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var SlackClient $slackClient
     */
    private $slackClient;
    /**
     * @var Environment $env
     */
    private $env;
    /**
     * SlackImplementation constructor.
     * @param Environment $environment
     * @param LoggerInterface $logger
     * @param SlackClient $slackClient
     */
    public function __construct(
        Environment $environment,
        SlackClient $slackClient,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->env = $environment;
        $this->slackClient = $slackClient;
    }
    /**
     * @param string $title
     * @param string $channel
     * @param string $text
     * @throws \Http\Client\Exception
     */
    public function sendMessageToChannel(
        string $title,
        string $channel,
        string $text
    ) {
        try {
            $metadata = new Metadata(
                $title,
                $channel,
                [$text]
            );

            $this->slackClient->send($metadata);
        } catch (\Exception $e) {
            $this->logger->warning(sprintf(
                'Slack failed to send a message to channel \'%s\'. An exception occurred. The message was: %s. Exception message: %s',
                $channel,
                $text,
                $e->getMessage()
            ));
        }
    }
}