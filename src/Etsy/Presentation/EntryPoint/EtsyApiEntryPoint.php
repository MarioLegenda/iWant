<?php

namespace App\Etsy\Presentation\EntryPoint;

use App\Etsy\Business\Finder;
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

    public function search(EtsyApiModel $model)
    {
        $this->finder->search($model);
    }
}