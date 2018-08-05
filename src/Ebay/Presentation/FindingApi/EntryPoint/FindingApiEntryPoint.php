<?php

namespace App\Ebay\Presentation\FindingApi\EntryPoint;

use App\Ebay\Business\Finder;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;

class FindingApiEntryPoint
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * FindingApiEntryPoint constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;
    }
    /**
     * @param FindingApiRequestModelInterface $model
     */
    public function query(FindingApiRequestModelInterface $model)
    {
        $xmlResponseModel = $this->finder->query($model);
    }
}