<?php

namespace App\Symfony\Listener;

use App\Library\Http\Response\ApiSDK;
use App\Library\Slack\SlackClient;
use App\Library\Util\Environment;
use Psr\Log\LoggerInterface;

class BaseHttpResponseListener
{
    /**
     * @var LoggerInterface $logger
     */
    protected $logger;
    /**
     * @var ApiSDK $apiSdk
     */
    protected $apiSdk;
    /**
     * @var Environment $environment
     */
    protected $environment;
    /**
     * @var SlackClient $slackClient
     */
    protected $slackClient;
    /**
     * BaseHttpResponseListener constructor.
     * @param LoggerInterface $logger
     * @param ApiSDK $apiSDK
     * @param Environment $environment
     * @param SlackClient $slackClient
     */
    public function __construct(
        LoggerInterface $logger,
        ApiSDK $apiSDK,
        Environment $environment,
        SlackClient $slackClient
    ) {
        $this->slackClient = $slackClient;
        $this->logger = $logger;
        $this->apiSdk = $apiSDK;
        $this->environment = $environment;
    }
}