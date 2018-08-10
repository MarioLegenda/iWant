<?php

namespace App\Bonanza\Source;

use App\Bonanza\Library\Request;
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

    public function search(Request $request)
    {
        $this->bonanzaRepository->search($request);
    }
}