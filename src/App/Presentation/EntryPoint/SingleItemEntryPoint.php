<?php

namespace App\App\Presentation\EntryPoint;

use App\App\Business\Finder;
use App\App\Presentation\Model\Request\SingleItemRequestModel;

class SingleItemEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * SingleItemEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param SingleItemRequestModel $model
     * @return mixed
     */
    public function getSingleItem(SingleItemRequestModel $model)
    {
        return $this->finder->getSingleItem($model);
    }
}