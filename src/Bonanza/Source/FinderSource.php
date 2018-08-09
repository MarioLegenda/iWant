<?php

namespace App\Bonanza\Source;

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
}