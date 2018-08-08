<?php

namespace App\Amazon\Source\Repository;

use App\Amazon\Source\GenericHttpCommunicator;

class ProductAdvertisingApiRepository
{
    /**
     * @var GenericHttpCommunicator $communicator
     */
    private $communicator;
    /**
     * ProductAdvertisingApiRepository constructor.
     * @param GenericHttpCommunicator $communicator
     */
    public function __construct(
        GenericHttpCommunicator $communicator
    ) {
        $this->communicator = $communicator;
    }
    /**
     * @param string $url
     * @return string
     */
    public function getResource(string $url): string
    {
        return $this->communicator->get($url);
    }
}