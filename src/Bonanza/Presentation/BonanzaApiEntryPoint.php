<?php

namespace App\Bonanza\Presentation;

use App\Bonanza\Business\Finder;
use App\Bonanza\Presentation\Model\BonanzaApiModelInterface;

class BonanzaApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * BonanzaApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }

    public function search(BonanzaApiModelInterface $model)
    {
        $this->finder->search($model);
    }
}