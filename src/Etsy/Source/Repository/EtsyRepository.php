<?php

namespace App\Etsy\Source\Repository;

use App\Etsy\Source\GenericHttpCommunicator;
use App\Library\Http\Request;
use App\Library\OfflineMode\OfflineMode;
use App\Library\Response;

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
     * @param Request $request
     * @return Response
     */
    public function getResource(Request $request): Response
    {
        return $this->communicator->get($request);
    }
}