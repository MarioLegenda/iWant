<?php

namespace App\Bonanza\Source;

use App\Library\Http\Request;
use App\Bonanza\Source\Repository\BonanzaRepository;

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

    public function getResource(Request $request)
    {
        $this->bonanzaRepository->getResource($request);
    }
}