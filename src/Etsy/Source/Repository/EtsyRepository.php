<?php

namespace App\Etsy\Source\Repository;

use App\Etsy\Source\GenericHttpCommunicator;
use App\Library\OfflineMode\OfflineMode;

class EtsyRepository
{
    /**
     * @var GenericHttpCommunicator $communicator
     */
    private $communicator;
    /**
     * @var string $env
     */
    private $env;
    /**
     * EtsyRepository constructor.
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
    public function get(string $url): string
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