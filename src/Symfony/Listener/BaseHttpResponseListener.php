<?php

namespace App\Symfony\Listener;

use App\Library\Http\Response\ApiSDK;
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
     * BaseHttpResponseListener constructor.
     * @param LoggerInterface $logger
     * @param ApiSDK $apiSDK
     * @param Environment $environment
     */
    public function __construct(
        LoggerInterface $logger,
        ApiSDK $apiSDK,
        Environment $environment
    ) {
        $this->logger = $logger;
        $this->apiSdk = $apiSDK;
        $this->environment = $environment;
    }
}