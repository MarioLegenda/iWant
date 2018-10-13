<?php

namespace App\Library\Util;

use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;

class SlackImplementation
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var Client $nexySlack
     */
    private $nexySlack;
    /**
     * @var Environment $env
     */
    private $env;
    /**
     * SlackImplementation constructor.
     * @param Environment $environment
     * @param Client $nexySlack
     * @param LoggerInterface $logger
     */
    public function __construct(
        Environment $environment,
        Client $nexySlack,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->nexySlack = $nexySlack;
        $this->env = $environment;
    }
    /**
     * @param string $channel
     * @param string $text
     * @throws \Http\Client\Exception
     */
    public function sendMessageToChannel(
        string $channel,
        string $text
    ) {
        try {
            $message = $this->nexySlack->createMessage();

            $message
                ->to($channel)
                ->setText($text);

            $this->nexySlack->sendMessage($message);
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