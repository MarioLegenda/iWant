<?php

namespace App\Etsy\Presentation\EntryPoint;

use App\Etsy\Business\Finder;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Presentation\Model\EtsyApiModel;

class EtsyApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * EtsyApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param EtsyApiModel $model
     * @return EtsyApiResponseModelInterface
     */
    public function search(EtsyApiModel $model): EtsyApiResponseModelInterface
    {
        return $this->finder->search($model);
    }
}