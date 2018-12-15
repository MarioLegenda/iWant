<?php

namespace App\Yandex\Source\Repository;

use App\Library\Http\Request;
use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Response\ResponseModelInterface;

class Repository
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
     * @return ResponseModelInterface
     */
    public function getResource(Request $request): ResponseModelInterface
    {
        return $this->communicator->post($request);
    }
}