<?php

namespace App\Ebay\Source\Repository;

use App\Ebay\Source\GenericHttpCommunicator;
use App\Library\Http\Request;
use App\Library\OfflineMode\OfflineMode;
use App\Library\Response;
use App\Library\Tools\MemcachedWrapper;

class FindingApiRepository
{
    /**
     * @var MemcachedWrapper $memcachedWrapper
     */
    private $memcachedWrapper;
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
     * @param MemcachedWrapper $memcachedWrapper
     * @param GenericHttpCommunicator $communicator
     * @param string $env
     */
    public function __construct(
        MemcachedWrapper $memcachedWrapper,
        GenericHttpCommunicator $communicator,
        string $env
    ) {
        $this->memcachedWrapper = $memcachedWrapper;
        $this->communicator = $communicator;
        $this->env = $env;
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function getResource(Request $request): Response
    {
        if ($this->env === 'test') {
            $stringResponse =  OfflineMode::inst()->getResponse(
                $this->communicator,
                $request
            );

            return new Response(
                $request,
                $stringResponse,
                200,
                null
            );
        }

        return $this->communicator->get($request);
    }
}