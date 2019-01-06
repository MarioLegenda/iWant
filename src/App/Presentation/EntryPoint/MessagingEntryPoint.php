<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Presentation\Model\Request\Message;
use App\Library\Slack\Metadata;
use App\Library\Slack\SlackClient;
use Psr\Log\LoggerInterface;

class MessagingEntryPoint
{
    /**
     * @var SlackClient $slackClient
     */
    private $slackClient;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * MessagingEntryPoint constructor.
     * @param LoggerInterface $logger
     * @param SlackClient $slackClient
     */
    public function __construct(
        LoggerInterface $logger,
        SlackClient $slackClient
    ) {
        $this->logger = $logger;
        $this->slackClient = $slackClient;
    }
    /**
     * @param Message $message
     */
    public function sendMessageToSlack(Message $message): void
    {
        try {
            $this->slackClient->send(new Metadata(
                sprintf('A message from %s', $message->getName()),
                '#messages',
                [$message->getMessage()]
            ));

            $this->logger->info(sprintf(
                'A message has been sent to slack from %s with message: \'%s\'',
                $message->getName(),
                $message->getMessage()
            ));

        } catch (\Throwable $e) {
            $this->logger->critical(sprintf(
                'A message has failed to be sent to slack from %s with message: \'%s\'',
                $message->getName(),
                $message->getMessage()
            ));
        }
    }
}