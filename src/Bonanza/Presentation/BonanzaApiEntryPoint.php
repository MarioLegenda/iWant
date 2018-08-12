<?php

namespace App\Bonanza\Presentation;

use App\Bonanza\Business\Finder;
use App\Bonanza\Library\Response\BonanzaApiResponseModel;
use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
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
    /**
     * @param BonanzaApiModelInterface $model
     * @return BonanzaApiResponseModelInterface
     */
    public function search(BonanzaApiModelInterface $model): BonanzaApiResponseModelInterface
    {
        return $this->finder->search($model);
    }
}