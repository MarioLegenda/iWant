<?php

namespace App\Bonanza\Source;

use App\Bonanza\Source\Repository\BonanzaRepository;
use App\Library\Http\Request;
use App\Library\Response;

class FinderSource
{
    /**
     * @var BonanzaRepository $bonanzaRepository
     */
    private $bonanzaRepository;
    /**
     * FinderSource constructor.
     * @param BonanzaRepository $bonanzaRepository
     */
    public function __construct(
        BonanzaRepository $bonanzaRepository
    ) {
        $this->bonanzaRepository = $bonanzaRepository;
    }
    /**
     * @param Request $request
     * @return string
     */
    public function getResource(Request $request): string
    {
        /** @var Response $response */
        $response = $this->bonanzaRepository->getResource($request);

        return $response->getResponseString();
    }
}