<?php

namespace App\Symfony\Command\Util;

use App\Library\Util\SlackImplementation;
use App\Symfony\Command\BaseCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SlackMessage extends BaseCommand
{
    /**
     * @var SlackImplementation $slackImplementation
     */
    private $slackImplementation;
    /**
     * SlackMessage constructor.
     * @param SlackImplementation $slackImplementation
     */
    public function __construct(SlackImplementation $slackImplementation)
    {
        $this->slackImplementation = $slackImplementation;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:send_slack_message');

        $this->addArgument('title', InputArgument::REQUIRED, 'A title');
        $this->addArgument('channel', InputArgument::REQUIRED, 'A channel');
        $this->addArgument('message', InputArgument::REQUIRED, 'A message');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Http\Client\Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $title = $this->input->getArgument('title');
        $channel = $this->input->getArgument('channel');
        $message = $this->input->getArgument('message');

        $this->slackImplementation->sendMessageToChannel(
            $title,
            $channel,
            $message
        );
    }
}