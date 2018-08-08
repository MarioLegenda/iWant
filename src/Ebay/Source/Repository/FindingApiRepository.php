<?php

namespace App\Ebay\Source\Repository;

use App\Ebay\Source\GenericHttpCommunicator;
use App\Library\OfflineMode\OfflineMode;

class FindingApiRepository
{
    /**
     * @var string $env
     */
    private $env;
    /**
     * @var GenericHttpCommunicator $communicator
     */
    private $communicator;
    /**
     * FindingApiRepository constructor.
     * @param GenericHttpCommunicator $communicator
     * @param string $env
     */
    public function __construct(
        GenericHttpCommunicator $communicator,
        string $env
    ) {
        $this->communicator = $communicator;
        $this->env = $env;
    }
    /**
     * @param string $url
     * @return string
     */
    public function getResource(string $url): string
    {
        if ($this->env === 'dev' or $this->env === 'test') {
            return OfflineMode::inst()->getResponse(
                $this->communicator,
                $url
            );
        }

        return $this->communicator->get($url);
    }
}