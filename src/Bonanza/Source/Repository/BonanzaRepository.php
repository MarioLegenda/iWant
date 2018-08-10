<?php

namespace App\Bonanza\Source\Repository;

use App\Bonanza\Library\Request;
use App\Library\Http\GenericHttpCommunicatorInterface;

class BonanzaRepository
{
    /**
     * @var string $env
     */
    private $env;
    /**
     * @var GenericHttpCommunicatorInterface $communicator
     */
    private $communicator;
    /**
     * BonanzaRepository constructor.
     * @param GenericHttpCommunicatorInterface $communicator
     * @param string $env
     */
    public function __construct(
        string $env,
        GenericHttpCommunicatorInterface $communicator
    ) {
        $this->env = $env;
        $this->communicator = $communicator;
    }
    /**
     * @param Request $request
     */
    public function search(Request $request)
    {
        $this->communicator->post($request);
    }
}