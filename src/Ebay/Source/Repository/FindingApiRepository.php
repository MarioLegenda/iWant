<?php

namespace App\Ebay\Source\Repository;

use App\Ebay\Source\GenericHttpCommunicator;
use App\Library\Http\Request;
use App\Library\Response;

class FindingApiRepository
{
    /**
     * @var GenericHttpCommunicator $communicator
     */
    private $communicator;
    /**
     * FindingApiRepository constructor.
     * @param GenericHttpCommunicator $communicator
     */
    public function __construct(
        GenericHttpCommunicator $communicator
    ) {
        $this->communicator = $communicator;
    }
    /**
     * @param Request $request
     * @return Response
     * @throws \App\Symfony\Exception\ExternalApiNativeException
     */
    public function getResource(Request $request): Response
    {
        return $this->communicator->get($request);
    }
}